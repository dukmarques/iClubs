<template>
    <div class="container">
        <Modal v-show="showModal" @closeModal="closeModal">
            <div class="updateUser">
                <h1>Adicione novo Assinante</h1>
                <form @submit.prevent="updateUser">
                    <input type="text" v-model="editName" placeholder="Nome">
                    <input type="email" v-model="editEmail" placeholder="E-mail">
                    <button type="submit">Editar</button>
                </form>
            </div>
        </Modal>

        <header>
            <div class="user">
                <div class="photo">
                    <img src="@/assets/user.png" alt="User">
                </div>
                <div class="dataUser">
                    <span><strong>Nome:</strong> {{ userData.name }}</span>
                    <span><strong>E-mail:</strong> {{ userData.email }}</span>
                    <span>Última atualização: {{
                            new Intl.DateTimeFormat('pt-BR').format(new Date(userData.updated_at))
                    }}</span>
                </div>
            </div>

            <div class="actions">
                <button @click="showModal = true">Editar</button>
                <button @click="showModal = true">Nova assinatura</button>
                <button @click="deleteUser">Excluir</button>
            </div>
        </header>

        <div class="content">
            <header>
                <h1>Clubes Assinados</h1>
                <span>* Clique em um clube para ver as faturas</span>
            </header>

            <table>
                <thead>
                    <tr>
                        <th>Clube</th>
                        <th>Descrição</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="club in clubs" :key="club.id">
                        <td>{{ club.name }}</td>
                        <td>{{ club.description ?? ' - ' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import Modal from '../components/Modal.vue';
import { createToast } from 'mosha-vue-toastify';
import 'mosha-vue-toastify/dist/style.css';

export default {
    name: 'UserDetails',
    data() {
        return {
            userData: null,
            clubs: [],
            showModal: false,
            editName: '',
            editEmail: ''
        }
    },
    components: {
        Modal
    },
    async created() {
        await this.getUserData();
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
        async getUserData() {
            await this.axios(`/user/${this.$route.params.id}`)
                .then((response) => {
                    this.userData = response.data[0];
                    this.clubs = response.data[0].clubs;
                })
                .catch((error) => {
                    const { response } = error;
                    this.toast('Erro!', response.data.error);
                });
        },
        async deleteUser() {
            await this.axios.delete(`/user/${this.$route.params.id}`)
                .then((response) => {
                    this.toast('Sucesso!', `Usuário ${response.data[0].name} excluído com sucesso`);
                    this.$router.push('/users');
                })
                .catch((error) => {
                    const { response } = error;
                    this.toast('Erro!', response.data.error);
                });
        },
        async updateUser() {
            let payload = {};
            if (this.editName !== '') {
                payload.name = this.editName;
            }

            if (this.editEmail !== '') {
                payload.email = this.editEmail;
            }

            await this.axios.put(`/user/${this.$route.params.id}`, payload)
                .then((response) => {
                    this.toast('Sucesso!', `Usuário ${response.data[0].name} editado com sucesso`);
                    this.getUserData();
                    this.closeModal();
                })
                .catch((error) => {
                    const { data } = error.response;
                    if (data.error.name) {
                        this.toast('Erro!', data.error.name[0]);
                    } else if (data.error.email) {
                        this.toast('Erro!', data.error.email[0]);
                    }
                });
        },
        closeModal() {
            this.showModal = false;
        },
    }

}
</script>

<style scoped lang="scss">
.container {
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    animation: fadeIn 2s;

    .updateUser {
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

    header {
        margin-top: 5px;
        padding: 15px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;

        border-bottom: 1px solid var(--green-dark);

        .user {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;

            .photo {
                border-radius: 50%;
                background: linear-gradient(90deg, var(--green-dark) 0%, var(--green) 100%);
                padding: 3px;
                display: flex;
                justify-content: center;
                align-items: center;

                img {
                    width: 75px;
                    border-radius: 50%;
                    background: var(--black);

                }
            }

            .dataUser {
                display: flex;
                flex-direction: column;

                font-family: 'Roboto';
                font-weight: 400;
                font-size: 16px;
                line-height: 24px;
                color: #C4C4CC;
            }
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 15px;

            button {
                border: none;
                background: var(--green-dark);
                color: var(--white);
                padding: 10px 20px;
                border-radius: 5px;
                transition: filter 0.3s;

                &:hover {
                    filter: brightness(0.8);
                }

                &:last-child {
                    background-color: var(--red);
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

        header {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            border-bottom: none;

            font-family: 'Roboto';
            font-weight: 400;
            color: #C4C4CC;

            span {
                font-size: 12px;
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