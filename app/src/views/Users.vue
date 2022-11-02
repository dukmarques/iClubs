<template>
    <div class="container">
        <Modal v-show="showModal" @closeModal="closeModal">
            <div class="registerUser">
                <h1>Adicione novo Assinante</h1>
                <form @submit.prevent="createUser">
                    <input type="text" v-model="nameUser" placeholder="Nome">
                    <input type="email" v-model="emailUser" placeholder="E-mail">
                    <button type="submit">Cadastrar</button>
                </form>
            </div>
        </Modal>

        <div class="content">
            <Header @buttonHeader="showModal = true" title="Lista de Assinantes" titleButton="Novo Assinante" />

            <input type="search" placeholder="Busque um usuário">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="user in users" :key="user.id" @click="goToUserDetails(user.id)">
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import Header from '../components/Header.vue';
import Modal from '../components/Modal.vue';
import { createToast } from 'mosha-vue-toastify';
import 'mosha-vue-toastify/dist/style.css';

export default {
    name: 'Users',
    data() {
        return {
            users: null,
            showModal: false,
            nameUser: '',
            emailUser: ''
        }
    },
    components: {
        Header,
        Modal
    },
    async created() {
        await this.getUsers();
    },
    setup() {
        const toast = (title, description = '', type = 'default', toastBackgroundColor) => {
            createToast({
                title,
                description
            },
                {
                    showIcon: false,
                    transition: 'zoom',
                    type,
                    hideProgressBar: false,
                    timeout: 3600,
                    toastBackgroundColor,
                })
        }
        return { toast }
    },
    methods: {
        async getUsers() {
            try {
                const res = await this.axios.get('/users');
                this.users = res.data;
            } catch (error) {
                toast('Error ao obter usuário');
            }
        },
        closeModal() {
            this.showModal = false;
        },
        async createUser() {
            await this.axios.post('/users', { name: this.nameUser, email: this.emailUser })
                .then((response) => {
                    this.toast('Sucesso!', `Usuário ${response.data[0].name} criado com sucesso!`);
                    this.getUsers();
                    this.closeModal();
                })
                .catch((error) => {
                    const { response } = error;
                    if (response.data.error.name) {
                        this.toast('Erro!', response.data.error.name[0]);
                    } else if (response.data.error.email) {
                        this.toast('Erro!', response.data.error.email[0]);
                    }
                });
        },
        goToUserDetails(id) {
            this.$router.push({ name: 'userDetails', params: { id } });
        }
    }
}
</script>

<style scoped lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap');

.container {
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    animation: fadeIn 2s;

    .registerUser {
        width: auto;
        height: 100%;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        align-items: center;

        form {
            width: 80%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;

            input,
            button {
                width: 100%;
                padding: 10px 15px;
                border: 1px solid var(--green-dark);
                border-radius: 5px;
            }

            input {
                border: 1px solid var(--gray);
                transition: focus 0.3s;

                &:focus {
                    outline: none !important;
                    border: 1px solid var(--green);
                }
            }

            button {
                color: var(--white);
                background-color: var(--green);
                border: none;
                filter: background 0.3s;

                &hover {
                    background: var(--green-dark);
                }
            }
        }
    }

    .content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 0 15px;

        input {
            width: 100%;
            padding: 10px 15px;
            background: #121214;
            border-radius: 6px;
            border: 1px solid var(--green-dark);

            font-family: 'Roboto';
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            color: #C4C4CC;

            &:focus {
                outline: none !important;
                border: 1px solid var(--green);
            }
        }

        table {
            margin-top: 15px;
            width: 100%;
            border-spacing: 0px 8px;

            th {
                font-family: 'Roboto';
                font-weight: 400;
                font-size: 16px;
                line-height: 24px;
                color: #C4C4CC;
            }

            tr {
                cursor: pointer;
                transition: filter 0.2s;

                &:hover {
                    filter: brightness(0.8);
                }

                td {
                    font-family: 'Roboto';
                    font-weight: 400;
                    font-size: 16px;
                    line-height: 24px;
                    color: #C4C4CC;

                    background: #29292E;
                    border-radius: 5px 0px 0px 5px;
                    padding: 15px 8px;
                    margin: 0px;
                    text-align: center;
                    border: 0;
                    color: #C4C4CC;

                    &:first-child {
                        border-radius: 5px 0px 0px 5px;

                    }

                    &:last-child {
                        border-radius: 0px 5px 5px 0px;
                    }
                }
            }
        }
    }
}

@keyframes fadeIn {
    0% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}
</style>