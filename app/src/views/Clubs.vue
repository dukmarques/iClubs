<template>
    <div class="container">
        <Modal v-show="showModal" @closeModal="closeModal">
            <div class="registerClub">
                <h1>Adicione novo Clube</h1>
                <form @submit.prevent="createClub">
                    <input type="text" v-model="clubName" placeholder="Nome">
                    <input type="text" v-model="clubDescription" placeholder="Descrição">
                    <button type="submit">Cadastrar</button>
                </form>
            </div>
        </Modal>

        <Header @buttonHeader="showModal = true" title="Lista de Clubes" titleButton="Novo Clube" />

        <div class="content">
            <div class="item" v-for="club in clubs" :key="club.id">
                <a href="#" class="card">
                    <header>
                        <h2>{{ club.name }}</h2>
                        <img src="@/assets/shield.png" alt="Clube">
                    </header>
                    <span>{{ club.description }}</span>
                </a>
            </div>
        </div>
    </div>
</template>
  
<script>
import Header from '../components/Header.vue';
import Modal from '../components/Modal.vue';
import { createToast } from 'mosha-vue-toastify';
import 'mosha-vue-toastify/dist/style.css';

export default {
    name: 'Clubs',
    data() {
        return {
            clubs: null,
            showModal: false,
            clubName: '',
            clubDescription: ''
        }
    },
    components: {
        Header,
        Modal
    },
    async created() {
        await this.getClubs();
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
        async getClubs() {
            try {
                const res = await this.axios.get('/clubs');
                this.clubs = res.data;
            } catch (error) {
                toast('Error ao obter clubes');
            }
        },
        async createClub() {
            await this.axios.post('/clubs', { name: this.clubName, desription: this.clubDescription })
                .then((response) => {
                    this.toast('Sucesso!', `Clube ${response.data[0].name} criado com sucesso!`);
                    this.getClubs();
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
        closeModal() {
            this.showModal = false;
        },
    },
}
</script>

<style scoped lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap');

.container {
    width: 100%;
    max-width: 1120px;
    margin: 0 auto;
    animation: fadeIn 2s;

    .registerClub {
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
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: center;

        .item {
            width: 33%;
            margin: 15px 0px;
            display: flex;
            justify-content: center;
            align-items: center;

            .card {
                width: 320px;
                height: 120px;
                padding: 10px;
                background: linear-gradient(90deg, var(--green-dark) 0%, var(--green) 100%);
                border-radius: 5px;
                box-shadow: 0px 5px 15px -6px #10393B;

                display: flex;
                flex-direction: column;
                justify-content: space-between;

                position: relative;

                &:hover {
                    filter: brightness(0.9);
                }

                header {
                    display: flex;
                    justify-content: space-between;

                    h2 {
                        color: var(--white);
                        font-weight: 400;
                        font-family: 'Roboto';
                    }

                    img {
                        position: absolute;
                        top: 20px;
                        right: 10px;
                        width: 75px;
                    }
                }

                span {
                    color: var(--white);
                    font-weight: 400;
                    font-family: 'Roboto';
                    z-index: 9;
                }
            }
        }
    }
}

@media (max-width: 650px) {
    .container {
        .content {
            .item {
                width: 100% !important;

            }
        }
    }
}

@media (max-width: 980px) {
    .container {
        .content {
            .item {
                width: 50%;

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