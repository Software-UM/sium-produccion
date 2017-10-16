/**
 * Created by OSORIO on 20/05/2017.
 */
var i=0;

function timedCount() {
    i=i+1;
    postMessage(i);
    setTimeout("timedCount()", 30000);
}

timedCount();