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

        <Modal v-show="showModalSignature" @closeModal="closeModalSignature">
            <div class="signature">
                <h1>Adicionar nova assinatura</h1>
                <div class="clubs">
                    <ul>
                        <li v-for="club in clubsAvailable" :key="club.id">
                            <div class="club">
                                <img src="@/assets/shield.png" alt={{club.name}}>
                                <div>
                                    <span>{{ club.name }}</span>
                                    <span>{{ club.description }}</span>
                                </div>
                            </div>
                            <button @click="associateClubToUser(club)">Assinar</button>
                        </li>
                    </ul>
                </div>
            </div>
        </Modal>

        <Modal v-show="showModalInvoices" @closeModal="closeModalInvoices">
            <div class="invoices">
                <h1>Faturas</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Data de Vencimento</th>
                            <th>Status de Pagamento</th>
                            <th>Pagar Fatura</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="invoice in invoices" :key="invoice.id">
                            <td>{{ new Intl.DateTimeFormat('pt-BR').format(new Date(invoice.due_date)) }}</td>
                            <td>{{ invoice.payment_status === 'paid' ? 'Paga' : 'Não paga' }}</td>
                            <td>
                                <button v-if="invoice.payment_status === 'unpaid'"
                                    @click="payInvoice(invoice)">Pagar</button>
                                <button v-else class="paid">Paga</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                <button @click="showModalSignature = true">Nova assinatura</button>
                <button @click="deleteUser">Excluir</button>
            </div>
        </header>

        <div class="content">
            <header>
                <h1>Clubes Assinados</h1>
            </header>

            <table>
                <thead>
                    <tr>
                        <th>Clube</th>
                        <th>Descrição</th>
                        <th>Status da Assinatura</th>
                        <th>Faturas</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="club in clubs" :key="club.id">
                        <td>{{ club.name }}</td>
                        <td>{{ club.description ?? ' - ' }}</td>
                        <td>{{ club.signature.status === 'active'
                                ? 'Ativa' : club.signature.status === 'defaulter'
                                    ? 'Inadimplente' : 'Inativo'
                        }}</td>
                        <td @click="openModalInvoices(club.signature)">
                            <img src="@/assets/fatura.png" alt="Faturas">
                        </td>
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
            showModalSignature: false,
            showModalInvoices: false,
            editName: '',
            editEmail: '',
            clubsAvailable: [],
            invoices: [],
            currentSignature: null
        }
    },
    components: {
        Modal
    },
    async created() {
        await this.getUserData();
        await this.getAvailableClubs();
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
        async getAvailableClubs() {
            await this.axios.get(`/club/available/${this.userData.id}`)
                .then((response) => {
                    this.clubsAvailable = response.data;
                })
                .catch((error) => {
                });
        },
        async associateClubToUser(club) {
            await this.axios.post(`/signatures/${this.userData.id}/${club.id}`)
                .then((response) => {
                    this.toast('Sucesso', `Assinatura com o time ${club.name} realizada com sucesso!`);
                    this.getUserData();
                    this.closeModalSignature();
                })
                .catch((error) => {
                    this.toast('Erro', `Aconteuceu um erro ao realizar assinatura!`);
                });
        },
        async payInvoice(invoice) {
            this.axios.put(`/invoice/${invoice.id}`, { status: 'paid' })
                .then((response) => {
                    this.toast('Sucesso', 'Fatura paga com sucesso!');
                })
                .catch((error) => {
                    this.toast('Erro', 'Houve um erro ao pagar a fatura!');
                })
                .finally(() => {
                    this.getInvoices(this.currentSignature);
                });
        },
        async getInvoices(signature) {
            this.currentSignature = signature;
            await this.axios.get(`/invoices/${signature.id}`)
                .then((response) => {
                    this.invoices = response.data;
                })
                .catch((error) => {

                });
        },
        closeModal() {
            this.showModal = false;
        },
        closeModalSignature() {
            this.showModalSignature = false;
        },
        async openModalInvoices(signature) {
            this.getInvoices(signature);
            this.showModalInvoices = true;
        },
        closeModalInvoices() {
            this.showModalInvoices = false;
            this.getUserData();
        }
    }

}
</script>

<style scoped lang="scss">
.container {
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    animation: fadeIn 1s;

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

    .signature {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        padding: 10px 0px;

        .clubs {
            overflow: scroll;
            height: 565px;
            width: 100%;
            padding: 20px;

            ul {
                display: flex;
                flex-direction: column;
                gap: 5px;

                li {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    padding: 8px 5px;
                    position: relative;
                    background: #10393B;
                    color: #fff;

                    position: relative;

                    .club {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;

                        img {
                            width: 50px;
                        }

                        div {
                            display: flex;
                            flex-direction: column;
                        }
                    }

                    button {
                        border: none;
                        background: var(--green);
                        position: absolute;
                        top: 0;
                        right: 0;
                        color: var(--white);
                        padding: 0px 10px;
                        height: 100%;
                        border-radius: 0px 7px 7px 0px;
                        transition: filter 0.3s;

                        &:hover {
                            filter: brightness(0.8);
                        }
                    }
                }
            }
        }
    }

    .invoices {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 10px;

        table {
            width: 100%;
            border-spacing: 0px 5px;

            th {
                font-family: 'Roboto';
                font-weight: 400;
                font-size: 14px;
            }

            tr {
                transition: filter 0.2s;

                &:hover {
                    filter: brightness(0.8);
                }

                td {
                    font-family: 'Roboto';
                    font-weight: 400;
                    font-size: 16px;
                    color: #C4C4CC;

                    background: #29292E;
                    padding: 5px 8px;
                    margin: 0px;
                    text-align: center;
                    border: 0;
                    color: #C4C4CC;

                    &:first-child {
                        border-radius: 5px 0px 0px 5px;
                    }

                    &:last-child {
                        border-radius: 0px 5px 5px 0px;
                        position: relative;
                    }

                    button {
                        border: none;
                        background-color: var(--red);
                        color: var(--white);
                        transition: filter 0.3s;
                        position: absolute;
                        top: 0;
                        right: 0;
                        height: 29px;
                        width: 90%;
                        border-radius: 0px 5px 5px 0px;

                        &.paid {
                            background: var(--green);
                            cursor: auto;
                        }

                        &:hover {
                            filter: brightness(0.9);
                        }
                    }
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
                    padding: 15px 8px;
                    margin: 0px;
                    text-align: center;
                    border: 0;
                    color: #C4C4CC;

                    img {
                        width: 24px;
                        cursor: pointer;

                        &:hover {
                            filter: brightness(0.8);
                        }
                    }

                    &:first-child {
                        border-radius: 5px 0px 0px 5px;

                    }

                    &:last-child {
                        border-radius: 0px 5px 5px 0px;
                        display: flex;
                        justify-content: center;
                        align-items: center;
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