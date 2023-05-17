$(document).ready(function()
{

    var getSummaryDateMonth;
    var dataSummaryPendingMonth = [];
    var dataSummaryActiveMonth = [];
    var dataSummaryDoneMonth = [];
    var dataSummaryTotalMonth = [];
    var ctxSummaryMonth = $("#canvasSummaryMonth");

    var dataDateDetailMonth = [];
    var dataDetailActiveMonth = [];
    var dataDetailPendingMonth = [];
    var dataDetailDoneMonth = [];
    var DataDetailTotalMonth = [];
    var ctxDetailMonth = $("#canvasDetailsMonth");

    var getSummaryDateYear;
    var dataSummaryActiveYear = [];
    var dataSummaryPendingYear = [];
    var dataSummaryDoneYear = [];
    var dataSummaryTotalYear = [];
    var ctxSummaryYear = $("#canvasSummaryYear");

    var dataDateDetailYear = [];
    var dataDetailActiveYear = [];
    var dataDetailPendingYear = [];
    var dataDetailDoneYear = [];
    var DataDetailTotalYear = [];
    var ctxDetailYear = $("#canvasDetailsYear");

    Date.prototype.getMonthName = function()
    {
        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        return monthNames[this.getMonth()];
    }

    $.get(urlSummaryMonth, function(response)
    {
        response.forEach(function(dataRes)
        {
            dataSummaryPendingMonth.push(dataRes.pending);
            dataSummaryActiveMonth.push(dataRes.active);
            dataSummaryDoneMonth.push(dataRes.done);
            dataSummaryTotalMonth.push(dataRes.total);
            getSummaryDateMonth = dataRes.date;
        });

        new Chart(ctxSummaryMonth,
        {
            type: 'bar',
            data:
            {
                labels: [new Date(getSummaryDateMonth).getMonthName()],
                datasets: dataGraphMonth(dataSummaryPendingMonth, dataSummaryActiveMonth, dataSummaryDoneMonth, dataSummaryTotalMonth)
            },
            options: option('Month')
        });
    });

    $.get(urlDetailMonth, function(response)
    {
        response.forEach(function(dataRes)
        {
            dataDateDetailMonth.push(new Date(dataRes.date).getDate());
            dataDetailPendingMonth.push(dataRes.pending);
            dataDetailActiveMonth.push(dataRes.active);
            dataDetailDoneMonth.push(dataRes.done);
            DataDetailTotalMonth.push(dataRes.total);
        });

        new Chart(ctxDetailMonth,
        {
            type: 'line',
            data:
            {
                labels: dataDateDetailMonth,
                datasets: dataGraphMonth(dataDetailPendingMonth, dataDetailActiveMonth, dataDetailDoneMonth, DataDetailTotalMonth)
            },
            options: option('Month')
        });
    });

    $.get(urlSummaryYear, function(response)
    {
        response.forEach(function(dataRes)
        {
            getSummaryDateYear = dataRes.date;
            dataSummaryActiveYear.push(dataRes.active);
            dataSummaryPendingYear.push(dataRes.pending);
            dataSummaryDoneYear.push(dataRes.done);
            dataSummaryTotalYear.push(dataRes.total);
        });

        new Chart(ctxSummaryYear,
        {
            type: 'bar',
            data:
            {
                labels: [new Date(getSummaryDateYear).getFullYear()],
                datasets: dataGraphMonth(dataSummaryPendingYear, dataSummaryActiveYear, dataSummaryDoneYear, dataSummaryTotalYear)
            },
            options: option('Year')
        });
    });

    $.get(urlDetailYear, function(response)
    {
        response.forEach(function(dataRes)
        {
            dataDateDetailYear.push(new Date(dataRes.new_date).getMonthName());
            dataDetailActiveYear.push(dataRes.active);
            dataDetailPendingYear.push(dataRes.pending);
            dataDetailDoneYear.push(dataRes.done);
            DataDetailTotalYear.push(dataRes.total);
        });

        new Chart(ctxDetailYear,
        {
            type: 'line',
            data:
            {
                labels: dataDateDetailYear,
                datasets: dataGraphMonth(dataDetailPendingYear, dataDetailActiveYear, dataDetailDoneYear, DataDetailTotalYear)
            },
            options: option('Year')
        });
    });
});

function dataGraphMonth(pending, active, done, total)
{
    return [
    {
        label: 'Pending',
        data: pending,
        borderWidth: 1,
        "fill": true,
        backgroundColor: 'rgb(144,147,153)',
        borderColor: 'rgb(108,110,115)',
        pointRadius: false,
        pointColor: 'rgb(90,92,96)',
        pointStrokeColor: '#51545a',
        pointHighlightFill: '#5d5858',
        pointHighlightStroke: 'rgb(96,88,88)',
    },
    {
        label: 'Active',
        data: active,
        borderWidth: 1,
        "fill": true,
        backgroundColor: 'rgb(170,191,124,1)',
        borderColor: 'rgb(170,191,124,1)',
        pointRadius: false,
        pointColor: 'rgb(90,92,96)',
        pointStrokeColor: '#21FF80',
        pointHighlightFill: '#21FF80',
        pointHighlightStroke: 'rgb(96,88,88)',
    },
    {
        label: "Done",
        data: done,
        borderWidth: 1,
        "fill": true,
        backgroundColor: 'rgba(60,141,188,0.9)',
        borderColor: 'rgba(60,141,188,0.8)',
        pointRadius: false,
        pointColor: '#3b8bba',
        pointStrokeColor: 'rgba(60,141,188,1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
    },
    {
        label: "Total",
        data: total,
        borderWidth: 1,
        "fill": true,
        backgroundColor: 'rgba(210, 214, 222, 1)',
        borderColor: 'rgba(210, 214, 222, 1)',
        pointRadius: false,
        pointColor: 'rgba(210, 214, 222, 1)',
        pointStrokeColor: '#c1c7d1',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
    }]
}

function option(type)
{
    return {
        responsive: true,
        title:
        {
            text: 'Token Detail This ' + type,
            display: true,
            position: "top",
            fontSize: 18,
            fontColor: "#111"
        },
        legend:
        {
            display: true,
            position: "bottom",
            labels:
            {
                fontColor: "#333",
                fontSize: 16
            }
        },
        scales:
        {
            xAxes: [
            {
                gridLines:
                {
                    display: false,
                }
            }],
            yAxes: [
            {
                gridLines:
                {
                    display: false,
                }
            }]
        }
    }
}