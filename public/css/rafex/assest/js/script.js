// Statistique Chart Start

var options2 = {
    series: [{
        name: 'Livre',
        data: [25000, 0, 0, 0, 0, 10000]
    }, {
        name: 'Non Livre',
        data: [5000, 0, 0, 0, 0, 8000]
    }],
    chart: {
        // height: 350,
        // width: 550,
        type: 'line',
        toolbar: {
            show: false,
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth'
    },
    title: {
        text: "Statistique de chiffre d'affaire par mois ",
        align: "left",
    },
    xaxis: {
        type: '',
        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jan", "Jul", "Aug", "Sep"]
    },
    yaxis: {
        title: {
            text: "",
        },
        min: 0,
        max: 30000,
    },
    legend: {
        position: "top",
        horizontalAlign: "right",
        floating: true,
        offsetY: -25,
        offsetX: -5,
    },

};

var chart = new ApexCharts(document.querySelector("#chartFirst"), options2);
chart.render();

///// Chart Two

var options = {
    series: [
        {
            name: "Livre",
            data: [618, 241, 0, 0, 0, 0, 0, 0, 0, 296],
        },
        {
            name: "Non Livre",
            data: [161, 61, 0, 0, 0, 0, 0, 0, 0, 131],
        },
    ],
    chart: {
        // height: 300,
        // width: 360,
        type: "line",
        dropShadow: {
            enabled: true,
            color: "#000",
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2,
        },
        toolbar: {
            show: false,
        },
    },
    colors: ["#77B6EA", "#545454"],
    dataLabels: {
        enabled: true,
    },
    stroke: {
        curve: "smooth",
    },
    title: {
        text: "Statistique de chiffre d'affaire par mois ",
        align: "left",
    },
    grid: {
        borderColor: "#e7e7e7",
        row: {
            colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
            opacity: 0.5,
        },
    },
    markers: {
        size: 1,
    },
    xaxis: {
        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        title: {
            text: "",
        },
    },
    yaxis: {
        title: {
            text: "",
        },
        min: 0,
        max: 618,
    },
    legend: {
        position: "top",
        horizontalAlign: "right",
        floating: true,
        offsetY: -25,
        offsetX: -5,
    },
};

var chart = new ApexCharts(document.querySelector("#chart"), options2);
chart.render();

// Statistique Chart End 

/// Chart 3rd Start

var options = {
    series: [{
        name: '',
        data: [5.3, 24.97, 16.23, 18.12, 13.25, 9.56, 8.62,]
    }],
    chart: {
        // height: 350,
        // width: 520,
        type: 'bar',
    },
    plotOptions: {
        bar: {
            borderRadius: 10,
            dataLabels: {
                position: 'top', // top, center, bottom
            },
        }
    },
    dataLabels: {
        enabled: true,
        formatter: function (val) {
            return val + "%";
        },
        offsetY: -20,
        style: {
            fontSize: '10px',
            colors: ["#304758"]
        }
    },

    xaxis: {
        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        position: 'top',
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false
        },
        crosshairs: {
            fill: {
                type: 'gradient',
                gradient: {
                    colorFrom: '#D8E3F0',
                    colorTo: '#BED1E6',
                    stops: [0, 100],
                    opacityFrom: 0.4,
                    opacityTo: 0.5,
                }
            }
        },
        tooltip: {
            enabled: true,
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false,
        },
        labels: {
            show: false,
            formatter: function (val) {
                return val + "%";
            }
        }

    },
    title: {
        text: 'Pourcenter de number de colis ajoutes chaque jour',
        floating: true,
        offsetY: 330,
        align: 'center',
        style: {
            color: '#444'
        }
    }
};

var chart = new ApexCharts(document.querySelector("#chartThird"), options);
chart.render();

// Chart 3rd End 








