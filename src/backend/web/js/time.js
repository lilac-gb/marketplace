const objHours = document.getElementById("hours");

const nameMonth = [
    "Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"
];
const nameDay = [
    "Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"
];

nowTime = () => {
    const time = new Date();

    let timeSec = time.getSeconds();
    let timeMin = time.getMinutes();
    let timeHours = time.getHours();

    timeWr = ((timeHours < 10) ? "0" : "") + timeHours;
    timeWr += ":";
    timeWr += ((timeMin < 10) ? "0" : "") + timeMin;
    timeWr += ":";
    timeWr += ((timeSec < 10) ? "0" : "") + timeSec + " ";

    timeWr = nameDay[time.getDay()] + ", " + time.getDate() + " " + nameMonth[time.getMonth()] + " " + time.getFullYear() + ", " + timeWr;

    objHours.innerHTML = timeWr;
};

nowTime();
setInterval("nowTime()", 1000);