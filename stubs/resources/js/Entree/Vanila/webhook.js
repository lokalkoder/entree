import axios from 'axios'
import mapValues from "lodash/mapValues";
import { usePage } from '@inertiajs/inertia-vue3'

export default class Webhook {

    /**
     * Request header declaration.
     *
     * @returns {{headers: {Authorization: string, Accept: string}}}
     */
    header = () => {
        return {
            headers: {
                'Authorization': `Bearer ${usePage().props.value.auth.user.token}`,
                'Accept': 'application/json'
            }
        }
    }

    /**
     * Permission check.
     *
     * @param permissionName
     * @param can
     * @returns {boolean}
     */
    can(permissionName, can) {

        let permission = can !== undefined ? can +'.'+ permissionName : permissionName

        return usePage().props.value.auth.permissions.indexOf(permission) !== -1;
    };

    /**
     * Clear error message.
     */
    clearErrorMessage() {
        // clear error message before make any call
        // alert only displayed if there's "change" happen..
        // success in succession will not trigger changed
        usePage().props.value.errors = {}
        usePage().props.value.messages.error = null
    }

    /**
     * Clear message.
     */
    clearmessage() {
        // clear error message before make any call
        // alert only displayed if there's "change" happen..
        // success in succession will not trigger changed
        usePage().props.value.messages.error = null
        usePage().props.value.messages.success = null
        usePage().props.value.messages.warning = null
    }

    /**
     * Date formatter.
     *
     * @param date
     * @returns {string|*}
     */
    formatDate(date) {
        if (date === null) {
            return date
        }

        if (typeof date !== 'object') {
            date = new Date(date);
        }

        const dd = String(date.getDate()).padStart(2, '0');
        const mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
        const yyyy = date.getFullYear();

        return dd + '-' + mm + '-' + yyyy;
    }

    /**
     * Date time formatter.
     *
     * @param date
     * @returns {string|*}
     */
    formatDateTime(date) {
        if (date === null) {
            return date
        }

        if (typeof date !== 'object') {
            date = new Date(date);
        }

        const dd = String(date.getDate()).padStart(2, '0');
        const mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!
        const yyyy = date.getFullYear();

        return dd + '-' + mm + '-' + yyyy + ' ' + date.getHours()  + ':' + date.getMinutes()  + ':' + date.getSeconds();
    }

    /**
     * Money formatter.
     *
     * @param value
     * @returns {string}
     */
    moneyFormater(value, simple = false) {
        const formatter = new Intl.NumberFormat('en-MY', {
            style: 'currency',
            currency: 'MYR',
        });

        let num = value;

        const isThousand = Math.abs(num) > 999 && Math.abs(num) < 999999
        const isMillion = Math.abs(num) > 999999

        if (simple) {

            if (isThousand) {
                num = Math.sign(num) * (Math.abs(num) / 1000).toFixed(1);
            }

            if (isMillion) {
                num = Math.sign(num) * (Math.abs(num) / 1000000).toFixed(1);
            }
        }

        return formatter.format(num) + (simple ? (isThousand ? 'K' : (isMillion ? 'M' : '' ) ) : '')
    }

    getYearList(year, after, before = 0) {
        const currentYear = (new Date(year, 0, 1)).getFullYear()

        let yearList = []

        for (let i = currentYear-after; i <= currentYear+before; i++) {
            yearList.push(i);
        }

        return yearList.sort(function(a, b){return b - a})
    }

    getMonthList(month) {
        if (month != null || month != undefined) {
            return this.getMonthList()[month]
        }

        if (month === null) {
            return null
        }

        return [
            "January", "February", "March","April","May","June",
            "July","August","September","October","November","December"
        ]
    }

    /**
     * Set progress status.
     *
     * @param status
     */
    inProgress(status) {
        usePage().props.value.is_loading = status
    }

    /**
     * Get paginate data.
     *
     * @param urlName
     * @param page
     * @param app
     * @returns {Promise<void>}
     */
    async paginatedData(urlName, page, app) {
        const  response = await this.paginate(route(urlName) + '?page=' + page)

        app.data = response.data
        app.total = parseInt(response.total)
        app.page = parseInt(response.per_page)
        app.current = parseInt(response.current_page)
        app.path = response.path
    }

    /**
     * Paginate bil.
     *
     * @param index
     * @param current
     * @param page
     * @returns {number}
     */
    paginateBil(index, current, page) {
        return parseInt(index) + 1 + (parseInt(current) - 1) * parseInt(page)
    }

    /**
     * Post action without response handling.
     *
     * @param url
     * @param data
     * @returns {Promise<unknown>}
     */
    post(url, data, message = 'Request successfully processed') {
        this.clearmessage()

        const vm = this
        this.inProgress(true)

        return new Promise((resolve, reject) => {
            axios.post(route(url), data, this.header())
                .then(res => {
                    vm.inProgress(false)
                    vm.clearErrorMessage()
                    usePage().props.value.messages.success = message
                    resolve(res.data)
                })
                .catch(err => {
                    vm.inProgress(false)
                    usePage().props.value.errors = err.response.data.errors
                    usePage().props.value.messages.error = err.response.data.message
                    reject(err)
                }).finally()
        })
    }

    /**
     * Patch action with response handling.
     *
     * @param url
     * @param id
     * @param data
     * @returns {Promise<unknown>}
     */
    patch(url, id, data, message) {
        this.clearmessage()

        const vm = this
        this.inProgress(true)

        return new Promise((resolve, reject) => {
            axios.patch(route(url, id), data, this.header())
                .then(res => {
                    vm.inProgress(false)
                    vm.clearErrorMessage()
                    usePage().props.value.messages.success = message ? message : 'Request successfully processed'
                    resolve(res.data)
                })
                .catch(err => {
                    vm.inProgress(false)
                    usePage().props.value.errors = err.response.data.errors
                    usePage().props.value.messages.error = err.response.data.message
                    reject(err)
                })
        })
    }

    /**
     * Get action with passed parameter without response handling.
     *
     * @param url
     * @param data
     * @returns {Promise<unknown>}
     */
    get(url, data) {
        this.clearmessage()

        const vm = this
        this.inProgress(true)

        return new Promise((resolve, reject) => {
            axios.get(route(url), vm.header())
                .then(res => {
                    vm.inProgress(false)
                    resolve(res.data)
                })
                .catch(err => {
                    vm.inProgress(false)
                    usePage().props.value.errors = err.response.data.errors
                    usePage().props.value.messages.error = err.response.data.message
                    reject(err)
                })
        })
    }

    /**
     * Delete action with response handling.
     *
     * @param url
     * @param id
     * @param data
     * @returns {Promise<unknown>}
     */
    delete(url, id, message) {
        this.clearmessage()

        const vm = this
        this.inProgress(true)

        return this.app.$swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                return new Promise((resolve, reject) => {
                    axios.delete(route(url, id), this.header())
                        .then(res => {
                            vm.inProgress(false)
                            vm.clearErrorMessage()
                            vm.app.$page.props.messages.success = (message) ? message : 'Request successfully processed'
                            resolve(res.data)
                        })
                        .catch(err => {
                            vm.inProgress(false)

                            const message = Object.values(err.response.data.errors).join('<br/>')

                            vm.app.$page.props.errors = err.response.data.errors
                            vm.app.$page.props.messages.error = message ?? err.response.data.message
                            reject(err)
                        })
                })
            }

            vm.inProgress(false)
        });
    }

    /**
     * Search specific data.
     *
     * @param url
     * @param data
     * @returns {Promise<unknown>}
     */
    search(url, data) {
        this.clearmessage()

        return new Promise((resolve, reject) => {
            axios.post(route(url), data, this.header())
                .then(res => {
                    // console.log(res)
                    resolve(res.data)
                })
                .catch(err => {
                    reject(err)
                })
        })
    }

    /**
     * Get specific item id data.
     *
     * @param url
     * @param data
     * @returns {Promise<unknown>}
     */
    find(url, id, data) {
        this.clearmessage()

        return new Promise((resolve, reject) => {
            axios.get(route(url, id), this.header())
                .then(res => {
                    // console.log(res)
                    resolve(res.data)
                })
                .catch(err => {
                    reject(err)
                })
        })
    }

    /**
     * Get paginated data.
     *
     * @param url
     * @param data
     * @returns {Promise<unknown>}
     */
    paginate(url) {
        return new Promise((resolve, reject) => {
            axios.get(url, this.header())
                .then(res => {
                    resolve(res.data)
                })
                .catch(err => {
                    reject(err)
                })
        })
    }

    /**
     * Get lookup data
     *
     * @param url
     * @param data
     * @returns {Promise<unknown>}
     */
    async lookup(url, data) {
        this.clearmessage()

        const response = await axios.get(route(url), this.header());

        return response.data;
    }

    /**
     * Clear form data parameter.
     *
     * @param form
     * @returns {*}
     */
    clearForm(form) {
        let vm = this

        return mapValues(form, function (v, x) {
            if (form[x] instanceof Array || form[x] instanceof Object) {
                return vm.clearForm(form[x])
            } else {
                return null;
            }
        })
    }
}
