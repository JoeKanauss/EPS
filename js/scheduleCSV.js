var date = new Date();
var monthName = ['January','February','March','April','May','June','July','August','September','October','November','December'];
var totalDays = [31,28,31,30,31,30,31,31,30,31,30,31];
var weekdayName = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
var day = date.getDate();
var weekday = date.getDay();
var month = date.getMonth();
var year = date.getFullYear();
var display = document.getElementById('date');
var yearDisplay = document.getElementById('year');
var monthDisplay = document.getElementById('month');
var daysDisplay =  document.getElementById('days');
var dateAndEventArray = [];
var contentToFile = '';

var testEvent = new Date(2019,02,24);


function setDays(date)
{
    //if leap year
    if(date.getFullYear() % 4 == 0)
    {
        //february has 29 days
        totalDays = [31,29,31,30,31,30,31,31,30,31,30,31];
    }

    var i=0;
    days.innerHTML = '';
    
    //firstDay is the first day of the current month and year
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    
    //if the firstDay is not a Sunday (day 0)
    if( firstDay.getDay() != 0)
    {
        //for each day previous to first day, mark that day as X (to format the calendar, these are days of the previous month, and take up space on the calendar)
        for(i=0; i < firstDay.getDay(); i++)
        {
           days.innerHTML = days.innerHTML + "<div class='xDay'>X</div>";
        }
    }

     //for each day of the month (using the totalDays array)...
    for(var x=1; x < totalDays[date.getMonth()]+1; x++)
    {
        //currentDay is the day of the of the month and year
      var currentDay = new Date(date.getFullYear(), date.getMonth(), x);

      //parse the currentDay into unique date string
      var parsedDay = Date.parse(currentDay);

      //use the parsedDay as id for the day, allowing it to be uniquely used with the setDate() and displayEvent() functions
      days.innerHTML = days.innerHTML + "<div id='"+parsedDay+"' class='day' onclick='setDate(this.id); displayEvent(this.id);'>"+ currentDay.getDate() + "</div>";

      // if the dataAndEventArray is found (see line 211 for details)
      if(dateAndEventArray != "unavailable")
      {
        //initialize the eventCount
        var eventCount = 0;
        //for each date/event in the dateAndEventArray...
        for (n = 0; n < dateAndEventArray[0].length; n++)
        {
            //create a date object from the date in the DateAndEventARray
            var thisDate = new Date(dateAndEventArray[0][n]);
            
            //if the currentDay is the same date as the date of the event
            if(String(currentDay) == String(thisDate))
            {
                //increase the eventCount
                eventCount = eventCount+1;
            }
            //if there is more than one event on this date...
            if(eventCount > 1)
            {
                //make the background color of the day yellow
                document.getElementById(parsedDay).style.backgroundColor = '	#ffffcc';
            }
            //if there is only one event on this date...
            else if (eventCount == 1)
            {
                //make the background color of the the day blue-green
                document.getElementById(parsedDay).style.backgroundColor = '#ccffe6';
            }
        }
      }
      //if the day of is the last day of the week (7 days to a week)..
      if((x+i) % 7 == 0)
      {
          //...create a new line for to start a new week
        days.innerHTML = days.innerHTML + "<div class='clear'></div>";
      }

      //format the event day
      formatEvent(parsedDay);

    } 

    //lastDay is the the number of days the current month has (ex. lastDay of March is 31)
    var lastDay = new Date(date.getFullYear(), date.getMonth(), totalDays[date.getMonth()]);

     //if the lastDay is not a saturday...
    if(lastDay.getDay() != 6)
    {
        for(i=lastDay.getDay()+1; i < 7; i++)
        {
            //...fill the rest of the days of the last week with X to indicate they are part of the next month
           days.innerHTML = days.innerHTML + "<div class='xDay'>X</div>";
        }
    }
    setFullDay();
}

function setFullDay(){
    console.log(dateAndEventArray[1]);
    
    for(var i=0; i<dateAndEventArray[0].length; i++){
    
        //get the date from the csv data
        var eventDate = dateAndEventArray[0][i];

        //the month of the event is the first set of numbers in the eventDate
        var eventMonth = eventDate.substring(0, 2);
        //the day of the event is the next set of numbers in the eventDate
        var eventDay = parseInt(eventDate.substring(3, 5));
        //the year of the event is the last set of numbers in the eventDate
        var eventYear = eventDate.substring(6, 10);
        //the eventDate is now the date object of the eventDate string
        var eventDate = new Date(eventYear, eventMonth-1, eventDay);

       var currentDay = dateAndEventArray[0][i];
       //parse the currentDay into unique date string
        var parsedDay = Date.parse(eventDate);
       
        // alert(parsedDay);
        // break;

        //add DAY FULL text if fullday is true
        if (dateAndEventArray[2][i] == "yes") {
                if(document.body.contains(document.getElementById(parsedDay))){
                    document.getElementById(parsedDay).innerHTML += "<span class='fullDay'>FULL DAY</span>";
                }
        }
        //alert(dateAndEventArray[2][i]);
    }
}

function displayEvent(date)
{
    //initialize eventsOfTheDay string
    var eventsOfTheDay = '';

    //for each day/Event in the dateAndEventArray...
    for(a=0; a<dateAndEventArray[0].length; a++)
    {
        //parse the event date into a unique date string
        var parsedEventDay = Date.parse(dateAndEventArray[0][a]);
        //if the date is the same as the parsedEventDay...
        if(date == parsedEventDay)
        {
            //add the event name to the string of events
            eventsOfTheDay = eventsOfTheDay + dateAndEventArray[1][a]+"<br>";
        }
    }
    //display the string of events
    document.getElementById('event-display').innerHTML = eventsOfTheDay;
}

function displayNext()
{
    //switch month up by one
    date.setMonth(date.getMonth()+1);
    //change name of month
    monthDisplay.innerHTML = monthName[date.getMonth()];
    //display year
    yearDisplay.innerHTML = date.getFullYear();
    //create the calendar
    setDays(date);
}

function displayLast()
{
    //switch the month down by one
    date.setMonth(date.getMonth()-1);
    //change name of month
    monthDisplay.innerHTML = monthName[date.getMonth()];
    //display year
    yearDisplay.innerHTML = date.getFullYear();
    //create the calendar
    setDays(date);
}

function setDate(cDateID)
{
    //get all of the days by their class and put them into an array
    var days = document.getElementsByClassName('day');

    //for each of the days
    for(var x=0; x < days.length; x++)
    {
        //if the background color of the day is bright red
        if(days[x].style.backgroundColor == 'rgb(255, 218, 204)')
        {
            //change the background color to white
            days[x].style.backgroundColor = 'white';
        }
        //if not...
        else
        {
            //keep iterating through days
            continue;
        }
    }
    //color changing is to indicate which date is currently selected
    //if the background color of the date is blue-green (a single event day)
    if( document.getElementById(cDateID).style.backgroundColor != "rgb(204, 255, 230)")
    {
        //change the color of the date to bright red
        document.getElementById(cDateID).style.backgroundColor = "#ffdacc";
    }
    //turn the unique date string into an object of that date
    var thisDate = new Date(parseInt(cDateID));

    //cDate is a string of the thisDate (ex monday-March-24-1990)
    var cDate = weekdayName[thisDate.getDay()]+"-"+monthName[thisDate.getMonth()]+"-"+thisDate.getDate()+"-"+thisDate.getFullYear();

    //get the day of the event
    var setEventDay = thisDate.getDate();
    //get the month of the event
    var setEventMonth = thisDate.getMonth()+1;

    //if the day of the event is one digit...
    if(thisDate.getDate()<10)
    {
       //add a leading 0 to day
       setEventDay = '0'+setEventDay;
    }
    
    //if the month of the event is one digit...
    if(thisDate.getMonth()<9)
    {
        //add a leading 0 to month
        setEventMonth='0'+setEventMonth;
    }

    //input date is formatted as (dd/mm/yyyy)
    var inputDate = setEventDay+"/"+(setEventMonth)+"/"+thisDate.getFullYear();

    //display the inputDate
    //document.getElementById('schedule-date').value = inputDate;
    
    //call resetEventScheduler() with the keyword 'event
    resetEventScheduler('event');
    
    //call disableSetEventButton() function
    //disableSetEventButton();    
}

function setEvent()
{
    //get the formatted date from the "schedule-date" input
    var eventDate = document.getElementById('schedule-date').value;
    //the day of the event is the first set of numbers in the eventDate
    var eventDay = eventDate.substring(0,2);
    //the month of the event is the next set of numbers in the eventDate
    var eventMonth = parseInt(eventDate.substring(3,5))-1;
    //the year of the event is the last set of numbers in the eventDate
    var eventYear = eventDate.substring(6,11);
    //the eventDate is now the date object of the eventDate string
    var eventDate = new Date(eventYear, eventMonth, eventDay);
    //the name of the event is the value of the "scheudle-event" input
    var eventName = document.getElementById('schedule-event').value;

    //make the background color of the date blue-green (for day with single event)
    document.getElementById(Date.parse(eventDate)).style.backgroundColor = '#ccffe6';
}

function formatEvent(parsedDay)
{
    // if the the parsedDay is equal to the unqiue date string of the testEvent...
    if(parsedDay == Date.parse(testEvent))
    {
        //make the background color of the testEvent blue-green
        document.getElementById(parsedDay).style.backgroundColor = "#ccffe6";
    }
}  

function getCSV(){
    //create an XMLHttpRequest object
    var xmlhttp = new XMLHttpRequest();
    //create a url to use
    var url = 'csvToJson.csv';
     //onstate change of the xmlhttprequest object..
    xmlhttp.onreadystatechange = function()
    {
         //if the object is ready...
         if(this.readyState == 4 && this.status == 200)
          {
             //stringify the responseText
             csvText = JSON.stringify(this.responseText);
             //strip the last comma from the string of objects 
             csvText = csvText.substring(0, csvText.length-2);
            //fill the dateAndEentArray with stringified Text using the display()
             dateAndEventArray = display(csvText);
          }
          else{
                
          }
          
    }
    //open the url
    xmlhttp.open("GET", url, true);
    //run the xmlhttp
    xmlhttp.send(); 

    // setTimeout(function(){csvUpload();},
    //     100);

    

    function display(csvText) {
        //split text into seperate lines on new lines and carriage returns
        var textLines = csvText.split(/\\r\\n/);
        //split line on tabs and commas
        var headers = textLines[0].split(/\\t|,/);
        var lines = [];
        var locations = [];
        //for each line...
        for (var i = 1; i < textLines.length; ++i) {
            //the data is separated by tabs or commas
            var data = textLines[i].split(/\t|,/);
            // //get the date from the csv data
            // var eventDate = data[1];
            // //the month of the event is the first set of numbers in the eventDate
            // var eventMonth = eventDate.substring(0, 2);
            // //the day of the event is the next set of numbers in the eventDate
            // var eventDay = parseInt(eventDate.substring(3, 5));
            // //the year of the event is the last set of numbers in the eventDate
            // var eventYear = eventDate.substring(6, 9);
            // //the eventDate is now the date object of the eventDate string
            // var eventDate = new Date(eventYear, eventMonth-1, eventDay);


            //if the there are equal amounts of data and headers... 
            if (data.length == headers.length) {
                //match up the headers
                var location = { "name": data[0], "date": data[1], "fullDay": data[2] };
                //add the data to the locations array
                locations.push(location);

            }
        }
        //initialize a datesArray
        var datesArray = [];
        //initialize an eventsArray
        var eventsArray = [];
        //initialuize a fullDayArray
        var fullDayArray = [];
        //for the length of the array parameter...
        for (var i = 0; i < locations.length; i++) {
            //add the date property form the provided array to the datesArray
            datesArray.push(locations[i].date);
            //add the name property from the provided array to the eventsArray
            eventsArray.push(locations[i].name);
            //add the full-day property from the provided array to the fullDayArray
            fullDayArray.push(locations[i].fullDay);
        }

        

        //return the created two-dimensional array 
        return [datesArray, eventsArray, fullDayArray];
    }

}

function testSave()
{
    //get the day of the formatted date in the "schedule-date" input
    var unformattedDay = document.getElementById('schedule-date').value.substring(0,2);
    //get the month of the formatted date in the "schedule-date" input
    var unformattedMonth = document.getElementById('schedule-date').value.substring(3,5);
    //get the year of the formatted date in the "schedule-date" input
    var unformattedYear = document.getElementById('schedule-date').value.substring(6,11);

    //format the date in the propery array for creating a date object
    var dateForArray = unformattedYear+","+unformattedMonth+","+unformattedDay;

    //add the json formatted date to the string of content to be put in a file
    contentToFile = contentToFile + '{"name":"'+document.getElementById('schedule-event').value+'","date": "'+dateForArray+'"},\r\n';
    
    //eventDate is the date object of the event date
    var eventDate = new Date(dateForArray);
    
    //add the formatted date to the date portion of the dateAndEventArray
    dateAndEventArray[0].push(dateForArray);
    //add the value of of the "schedule-event" input to the event portion of the dateAndEventArray
    dateAndEventArray[1].push(document.getElementById('schedule-event').value);

    //set the eventDate
    setDays(eventDate);
    //call the resetEventScheduler using the "all" keyword.
    resetEventScheduler('all');

    //return the string of content to be added to the a file
    return contentToFile;
}

function resetEventScheduler(toReset)
{
    //if the function keyword is "all"...
    if(toReset == 'all')
    {
        //reset the "schedule-date" input
        document.getElementById('schedule-date').value = '';
        //reset the "schedule-event" input
        document.getElementById('schedule-event').value = '';
        //reset the "event-display" area
        document.getElementById('event-display').innerHTML = '';
    }
    //if the function keyword is "date"...
    else if(toReset == 'date')
    {
        //reset the "schedule-date" input
        //document.getElementById('schedule-date').value = '';
    }
    //if the function keyword is "event"...
    else if(toReset == 'event')
    {
        //reset the "schedule-event" input
        //document.getElementById('schedule-event').value = '';
    }
}

function downloadSchedule()
{
    //if the contentToFile string is empty..
   if(contentToFile == '')
   {
        //alert "no content to include in schedule" message
        alert("no content to include in schedule");
   }
   else
   {
    //create a new file object
    var file = new File([contentToFile], 'schedule.txt', {type: 'text/plain', lastModified: Date.now()}); 
    
    // create an anchor element
    var anchor = document.createElement('a');
    //make the download property of the anchor the filename of the file object 
    anchor.download = 'schedule.txt';
    //the anchor creates a URL object of the file object
    anchor.href = URL.createObjectURL(file);
    //activate the anchor element
    anchor.click();
   }
}

function disableSetEventButton()
{
    //if the "schedule-date" input or the "schedule-event" input is empty...   
    if((document.getElementById('schedule-date').value =='') ||(document.getElementById('schedule-event').value==''))
    {
        //make the "set-event-button" button disabled
        document.getElementById('set-event-button').disabled = true;
    }
    else
    {
       //if the "schedule-date" input and the "schedule-event" input have a value, enable the "set-event-button" button   
        document.getElementById('set-event-button').disabled = false;
    }
}

function enterEventSchedule()
{
    //get the file from the "upload-schedule" input
    var fileToLoad = document.getElementById('upload-schedule').files[0];

    //create a fileReader object
    var fileReader = new FileReader();

    //when the fileReader object loads...
    fileReader.onload = function(fileLoadedEvent)
    {
        //the textFromFileLoaded is the result of the file uploaded
        var textFromFileLoaded = fileLoadedEvent.target.result;
        
        //the "event-display" area displays the data from the file
        document.getElementById('event-display').innerHTML = textFromFileLoaded;

        // parse JSON from uploaded file
    };
    
    //run the fileReader object
    fileReader.readAsText(fileToLoad, "UTF-8");
}

//disable the "set-event-button" buttonj
//disableSetEventButton();

//display the current month name
monthDisplay.innerHTML = monthName[date.getMonth()];

//display the current year
yearDisplay.innerHTML = date.getFullYear();

//bring in the current file of events
getCSV();
//after current events load, set the calendar with the dates and events
setTimeout(function(){
            setDays(date);
            },100);

