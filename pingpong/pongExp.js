var ball, court, paddle; 

var courtBallmargin;
var minY, maxY, minX, maxX; 

var vx, vy;
var vUnit;
var dt;
var timeout_n;
var intervalID;

function initialize(){
    setupGameVariables();
    setupGameBoundaries();
}

function resetGame(){
    // Y = a random value from range [min, maxTop]
    // X = 0
    updateBallCoord(0, getRandomCoord(minY, maxY));
}

function updateBallCoord(newX, newY){
    ball.style.left = newX + "px";
    ball.style.top = newY + "px";
}

function updateBallY(newY){
    ball.style.top = newY + "px";
}

function updateBallX(newX){
    ball.style.left = newX + "px";
}

function setupGameVariables(){
    ball = document.getElementById("ball");
    court = document.getElementById("court");
    paddle = document.getElementById("paddle");
    // velocity unit
    vUnit = (1/1000); // unit: 1 pexel/1000 miliseconds
    
    // velocity: unit pexel/milisecods
    vx = getRandomAngle(); 
    vy = getRandomAngle();
    
    // dt in milisecods
    dt = 5;

    // timeout_n
    timeout_n = 1000;

    // intervalID
    intervalID = null;
}

function setupGameBoundaries(){
    courtBallmargin = ball.getBoundingClientRect().left - court.getBoundingClientRect().left; 
    minY = -(ball.getBoundingClientRect().top - court.getBoundingClientRect().top) + courtBallmargin; 
    maxY = (court.getBoundingClientRect().top + court.getBoundingClientRect().height) - (ball.getBoundingClientRect().top + ball.getBoundingClientRect().height) - courtBallmargin;
    minX = 0;
    maxX = court.getBoundingClientRect().width - ball.getBoundingClientRect().width - courtBallmargin;   
}

function getYCoordBall(){
    if(ball.style.top.length == 0){
        return 0;  
    }else{
        return parseInt(ball.style.top);
    }
}
function getXCoordBall(){
    if(ball.style.left.length == 0){
        return 0;  
    }else{
        return parseInt(ball.style.left);
    }
}

function getRandomCoord(min, max){
    return Math.random() * (max - min + 1) + min;
}

// TODO: Move it later, just now it's just burried here
initialize();

function startGame(){
    setTimeout("moveBall()", 1000);
}

function moveBallInX(){ 
    if((getXCoordBall() > maxX) || (getXCoordBall() < minX)){
        vx = -vx;    
    } 
    console.log("maxX: " + maxX);
    console.log("vx: " + vx);

    console.log("old ball.style.left: " + ball.style.left);

    ball.style.left = (getXCoordBall() + vx).toString() + "px";
    
    console.log("new ball.style.left: " + ball.style.left);
}

function moveBallInY(){
    if((getYCoordBall() > maxY) || (getYCoordBall() < minY)){
        vy = -vy;
    }
    console.log("maxY: " + maxY);
    console.log("vy: " + vy);

    console.log("old ball.style.top: " + ball.style.top);

    ball.style.top = (getYCoordBall() + vy).toString() + "px";
    
    console.log("new ball.style.top: " + ball.style.top);    
}

function moveBallX(){
    for(var i = 0; i<1000000; i++){
        setTimeout("moveBallInX()", 3000/33);      
   }

    //while(true){ Yo! this does not work!
        //setTimeout("moveBallInX()", 1000);
        //moveBallInX();
    //}
}

function moveBallY(){
    for(var i = 0; i < 10000; i++){
      setTimeout("moveBallInY()", 3000/33);   
    }
}


function moveBallXY(){
    /*if((getXCoordBall() > maxX) || (getXCoordBall() < minX) 
        || (getYCoordBall() > maxY) || (getYCoordBall() < minY)){
        vx = -vx;   
        vy = -vy; 
    } 
    
    if(getXCoordBall() > maxX){
        vx = -vx;
    }else if(getXCoordBall() < minX){
        vx = -vx;
    }else if(getYCoordBall() > maxY){
        vy = -vy;
    }else if(getYCoordBall() < minY){
        vy = -vy;
    }*/

    var dt = getTic(); // millisecods

    var x = getXCoordBall();
    var y = getYCoordBall();

    
    y = y + (vy * dt);
    if(y < minY){
        vy = -vy;
        y = 2*minY - y;
    }
    if(y > maxY ){
        vy = -vy;
        y = 2*maxY - y
    }

    x = x + (vx * dt);
    if(x < minX){
        vx = -vx;
        x = 2*minX - x;
    } 
    if(x > maxX){
        vx = -vx;
        x = 2*maxX - x;
    }
    
    /*if(x > maxX || x < minX){
        vx = -vx;

    }
    if(y > maxY || y < minY){
        vy = -vy;
    }*/
    
    //x = Math.round(x);
    //y = Math.round(y);

    x = x + "px";
    y = y + "px";
    
    ball.style.left = x;    
    ball.style.top = y;

    logCoordMessage();
}

function getTic(){
    return dt;
}

function setSpeed(newTic){
    dt = newTic;
}

function setTimeout_n(n){
    timeout_n = n;
}
function getTimeout_n(){
    return timeout_n;
}

function moveBall(){
   //for(var i = 0; i < 1000; i++){
      //setTimeout("moveBallXY()", 2000);   
   //}  
    /*
    while(keepPlaying()){
      setTimeout("moveBallXY()", 1000/33);    
    }
     
    
    setTimeout("moveBallXY()", 1000/33);   

    if(endGame()){
        resetGame();
    }else {
        moveBall(); 
    }*/
    /*while(true){
        setTimeout("moveBallXY()",3000);
    }*/
    //while(getXCoordBall() < maxX){
       //setTimeout("moveBallXY()", 2000);   
    //}

    intervalID = setInterval(moveBallXY, 1000/33);
}

function stopGame(){
    clearInterval(intervalID);
    resetGame();
}

function getRandomAngle(){
    var maxAngle = Math.PI/4;
    var minAngle = -Math.PI/4;
    var ans = getRandomCoord(minAngle, maxAngle);
    while(Math.round(Math.abs(ans)) == 0){
        ans = getRandomCoord(minAngle, maxAngle);
    }
    return ans;
}


function logCoordMessage(){
    var msgEl = document.getElementById("messages");
    msgEl.innerHTML = "[x: " + getXCoordBall() + "px] " + "[y: " + getYCoordBall() + "px]";
}