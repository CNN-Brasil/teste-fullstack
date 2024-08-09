(() => {
    const template = document.querySelector('template#pscnn-loteria');

    if (template === null) {
        return;
    }

    Vue.component('pscnn-loteria', {
        template: template.innerHTML,
        props: {
            loteria: String,
            concurso: String | Number,
        },
        data() {
            return {
                colors: {
                    megasena: '#2D976A',
                    quina: '#261383',
                    lotofacil: '#921788',
                    lotomania: '#F58123',
                    timemania: '#3DAF3E',
                    duplasena: '#A41628',
                    federal: '#133497',
                    diadesorte: '#CA8536',
                    supersete: '#A9CF50',
                },
                weekDays: [
                    'Domingo',
                    'Segunda-Feira',
                    'Terça-Feira',
                    'Quarta-Feira',
                    'Quinta-Feira',
                    'Sexta-Feira',
                    'Sábado',
                ],
                data: {
                    loteria: {},
                },
            };
        },
        methods: {
            isFilled() {
                return Object.keys(this.data.loteria).length > 0;
            },
            getData(loteria, concurso) {
                const self = this;
                const url = `${pscnn.apiBaseUrl}hlsmelo/pscnn/v1/loterias`;

                axios.get(url, {
                    params: {
                        loteria,
                        concurso,
                    },
                })
                    .then((response) => self.data.loteria = response.data);
            },
            getWeekDay(date){
                if (date === undefined) {
                    return;
                }

                newDate = (date).split('/').reverse().join('-');
                newDate = new Date(newDate);

                return `${this.weekDays[newDate.getUTCDay()]} ${date}` ;
            },
            getCurrency(value) {
                return currency(value, { symbol: "R$ ", separator: ".", decimal: "," }).format();
            },
            getColor() {
                return this.colors[this.loteria];
            },
        },
        mounted() {
            this.getData(this.loteria, this.concurso);
        },
    });
})();
