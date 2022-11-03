<template>
    <div class="container">
        <Modal v-show="showModal" @closeModal="closeModal">
            <div class="updateClub">
                <h1>Editar clube {{ clubData.name }}</h1>
                <form @submit.prevent="updateClub">
                    <label for="">Nome</label>
                    <input type="text" v-model="editName" placeholder="Nome">

                    <label for="">Descrição</label>
                    <input type="text" v-model="editDescription" placeholder="Descrição">
                    <button type="submit">Editar</button>
                </form>
            </div>
        </Modal>

        <header>
            <div class="club">
                <img src="@/assets/shield.png" alt="User">
                <div class="dataClub">
                    <span><strong>Nome:</strong> {{ clubData.name }}</span>
                    <span><strong>Descrição:</strong> {{ clubData.description ?? ' - ' }}</span>
                    <span>Última atualização: {{
                            new Intl.DateTimeFormat('pt-BR').format(new Date(clubData.updated_at))
                    }}</span>
                </div>
            </div>

            <div class="actions">
                <button @click="openModal">Editar</button>
                <button @click="deleteClub">Excluir</button>
            </div>
        </header>

        <div class="content">
            <header>
                <h1>Assinantes</h1>
            </header>

            <table>
                <thead>
                    <tr>
                        <th>Clube</th>
                        <th>Descrição</th>
                        <th>Status da Assinatura</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.signature.status === 'active'
                                ? 'Ativo' : user.signature.status === 'defaulter'
                                    ? 'Inadimplente' : 'Inativo'
                        }}</td>
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
    name: 'ClubDetails',
    data() {
        return {
            clubData: '',
            users: [],
            showModal: false,
            editName: '',
            editDescription: ''
        }
    },
    components: {
        Header,
        Modal
    },
    async created() {
        await this.getClubeData();
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
        async getClubeData() {
            await this.axios(`/club/${this.$route.params.id}`)
                .then((response) => {
                    this.clubData = response.data[0];
                    console.log(this.clubData)
                    this.users = response.data[0].users;
                })
                .catch((error) => {
                    const { response } = error;
                    this.toast('Erro!', response.data.error);
                });
        },
        async deleteClub() {
            await this.axios.delete(`/club/${this.$route.params.id}`)
                .then((response) => {
                    this.toast('Sucesso!', `Clube ${response.data[0].name} excluído com sucesso`);
                    this.$router.push('/clubs');
                })
                .catch((error) => {
                    const { response } = error;
                    this.toast('Erro!', response.data.error);
                });
        },
        async updateClub() {
            let payload = {};
            if (this.editName !== '') {
                payload.name = this.editName;
            }

            if (this.editDescription === '') {
                payload.description = ' ';
            } else {
                payload.description = this.editDescription;
            }

            console.log(this.editDescription)

            await this.axios.put(`/club/${this.$route.params.id}`, payload)
                .then((response) => {
                    this.toast('Sucesso!', `Clube ${response.data[0].name} editado com sucesso`);
                    this.getClubeData();
                    this.closeModal();
                })
                .catch((error) => {
                    const { data } = error.response;
                    if (data.error.name) {
                        this.toast('Erro!', data.error.name[0]);
                    } else if (data.error.description) {
                        this.toast('Erro!', data.error.description[0]);
                    }
                });
        },
        openModal() {
            this.editName = this.clubData.name;
            this.editDescription = this.clubData.description;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },
    }
}
</script>

<style scoped lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap');

.container {
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    animation: fadeIn 1s;

    .updateClub {
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
            align-items: flex-start;
            gap: 15px;

            label {
                margin-bottom: -12px;
            }

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

        .club {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;

            img {
                width: 100px;
                background: var(--black);
            }

            .dataClub {
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