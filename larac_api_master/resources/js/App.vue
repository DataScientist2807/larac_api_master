<template>
   <div class="container">
        <!-- login form -->
         <div>
            <h3>Sign In for Tickets</h3>
            <form action="#" @submit.prevent="handleLogin">
                <input type="email" name="email" v-model="formData.email" placeholder="Email required">
                <input type="password" name="password" v-model="formData.password" placeholder="Password required">
                <button type="submit">Sign In</button>
            </form>
         </div>
         <div>
            <h3>Tickets List</h3>
            <div v-for="ticket in tickets.data" :key="ticket.id">
                    <div v-text="ticket.attributes.title"></div>
            </div>
         </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tickets: [],
                formData: {
                    email: '',
                    password: ''
                }
            }
        },
        methods: {
            handleLogin() {
                // send axios request to the login route
                axios.get('/sanctum/csrf-cookie').then(response => {
                    /* console.log(response); */
                    axios.post('/api/login', this.formData).then(response => {
                        this.getTickets();
                    });
                })
            },
            getTickets() {
                axios.get('/api/v1/tickets').then(response => {
                    this.tickets = response.data;
                });
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
