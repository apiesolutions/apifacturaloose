export const functions = {
    data() {
        return {
            loading_search_customer: false
        }
    },
    methods: {
        searchCustomerByNumber() {
            return new Promise((resolve) => {
                this.loading_search_customer = true
                let identity_document_type_name = ''
                if (this.form.identity_document_type_id === '06000006') {
                    identity_document_type_name = 'ruc'
                }
                if (this.form.identity_document_type_id === '06000001') {
                    identity_document_type_name = 'dni'
                }
                this.$http.get(`/services/${identity_document_type_name}/${this.form.number}`)
                    .then(response => {
                        console.log(response.data)
                        let res = response.data
                        if (res.success) {
                            this.form.name = res.data.name
                            this.form.trade_name = res.data.trade_name
                            this.form.address = res.data.address
                            this.form.department_id = res.data.department_id
                            this.form.province_id = res.data.province_id
                            this.form.district_id = res.data.district_id
                            this.form.phone = res.data.phone
                        } else {
                            this.$message.error(res.message)
                        }
                        resolve()
                    })
                    .catch(error => {
                        console.log(error.response)
                    })
                    .then(() => {
                        this.loading_search_customer = false
                    })
            })

        }
    }
};