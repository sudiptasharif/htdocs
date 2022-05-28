/**
 * Name: Sudipta Sharif
 */
var frame_rate;
var strikes;
var max_score;

var ball, court, paddle;

var courtBallmargin;
var minY, maxY, minX, maxX;

var vx, vy;
var dt;
var interval_id;

var paddleHeight;
var courtPaddlemargin;
var paddleMinY, paddleMaxY;

// inital diff between ball and paddle top
var courtPaddleOffset;
// to prevent bubbling of onclick event
var gameOn;

function initialize() {
    setupGameVariables();
    setupGameBoundaries();
    updateStrikes(strikes);
    updateMaxScore(max_score);
}
function movePaddle(event) {
    var mouse_y = event.pageY;
    if (court) {
        if ((mouse_y >= court.getBoundingClientRect().top + courtBallmargin) && (mouse_y <= court.getBoundingClientRect().top + court.getBoundingClientRect().height - courtBallmargin)) {
            movePaddleY(mouse_y - court.getBoundingClientRect().top - courtPaddlemargin);
        }
    }
}
function startGame() {
    if (!gameOn) {
        interval_id = setInterval(moveBallXY, frame_rate);
        gameOn = true;
    }
}
function resetGame() {
    clearInterval(interval_id);
    updateBallCoord(minX, getRandomCoord(minY, maxY));
    updateScoresBeforeReset();
    setSpeed(10); // did not do this! 
    gameOn = false;
}
function setSpeed(v) {
    if (v == 0) {
        dt = 10;
    } else if (v == 1) {
        dt = 15;
    } else {
        dt = 25;
    }
}
/************************ My Helper Functions *************************************/
function logCoordMessage() {
    var msgEl = document.getElementById("messages");
    msgEl.innerHTML = "Ball: [x: " + getXCoordBall() + "px] " + "[y: " + getYCoordBall() + "px]";
}
function logMouseCoord(x, y) {
    var msgEl = document.getElementById("messages");
    msgEl.innerHTML = "Mouse: [x: " + x + "px] " + "[y: " + y + "px]";
}
function getRandomCoord(min, max) {
    return Math.random() * (max - min) + min;
}
function getRandomAngle() {
    var maxAngle = Math.PI / 4;
    var minAngle = -Math.PI / 4;
    var ans = getRandomCoord(minAngle, maxAngle);
    while (Math.round(Math.abs(ans)) == 0) {
        ans = getRandomCoord(minAngle, maxAngle);
    }
    return ans;
}
function setupGameVariables() {
    frame_rate = 41.66; // most common cinetmatic frame rate in milliseconds
    strikes = 0;
    max_score = 0;

    ball = document.getElementById("ball");
    court = document.getElementById("court");
    paddle = document.getElementById("paddle");

    vx = getRandomAngle();
    vy = getRandomAngle();

    // initial tic
    dt = 10;
    // for clear setInterval() timer
    intervalID = null;

    gameOn = false;

    courtPaddleOffset = ball.getBoundingClientRect().top - paddle.getBoundingClientRect().top;
}
function setupGameBoundaries() {
    // Ball
    courtBallmargin = ball.getBoundingClientRect().left - court.getBoundingClientRect().left;
    minY = -(ball.getBoundingClientRect().top - court.getBoundingClientRect().top) + courtBallmargin;
    maxY = (court.getBoundingClientRect().top + court.getBoundingClientRect().height) - (ball.getBoundingClientRect().top + ball.getBoundingClientRect().height) - courtBallmargin;
    minX = 0;
    maxX = court.getBoundingClientRect().width - ball.getBoundingClientRect().width - courtBallmargin;

    // Paddle
    var offset = 2;
    courtPaddlemargin = paddle.getBoundingClientRect().top - court.getBoundingClientRect().top + offset; // an adjustment to fit the paddle neatly within the court;
    paddleHeight = paddle.getBoundingClientRect().height;
    paddleMinY = 0;
    paddleMaxY = court.getBoundingClientRect().height - paddle.getBoundingClientRect().height - courtPaddlemargin;
}
function updateBallCoord(newX, newY) {
    ball.style.left = newX + "px";
    ball.style.top = newY + "px";
}
function getYCoordBall() {
    if (ball.style.top.length == 0) {
        return 0;
    } else {
        return parseFloat(ball.style.top);
    }
}
function getXCoordBall() {
    if (ball.style.left.length == 0) {
        return 0;
    } else {
        return parseFloat(ball.style.left);
    }
}
function moveBallXY() {
    var x = getXCoordBall();
    var y = getYCoordBall();

    var paddle_top = getPaddleYCoord() - courtPaddleOffset;
    var paddle_bottom = paddle_top + paddleHeight;

    var paddle_left = parseFloat(paddle.style.left);

    y = y + (vy * dt);
    x = x + (vx * dt);

    if ((x >= paddle_left) && (y >= paddle_top && y <= paddle_bottom)) {
        vx = -vx;
        x = 2 * paddle_left - x;
        strikes++;
        updateStrikes(strikes);
    } else if (y < minY) {
        vy = -vy;
        y = 2 * minY - y;
    } else if (y > maxY) {
        vy = -vy;
        y = 2 * maxY - y
    } else if (x < minX) {
        vx = -vx;
        x = 2 * minX - x;
    } else if (x > maxX) {
        resetGame();
        return;
    }

    x = x + "px";
    y = y + "px";

    ball.style.left = x;
    ball.style.top = y;
    //logCoordMessage();
}
function getPaddleYCoord() {
    if (paddle.style.top.length == 0) {
        return 0;
    } else {
        return parseFloat(paddle.style.top);
    }
}
function getPaddleXCoord() {
    if (paddle.style.left.length == 0) {
        return 0;
    } else {
        return parseFloat(paddle.style.left)
    }
}
function movePaddleY(newY) {
    var paddle_y = newY;
    if (paddle_y >= paddleMinY && paddle_y <= paddleMaxY) {
        paddle.style.top = paddle_y + "px";
    }
}
function movePaddleByN(n) {
    var paddle_y = getPaddleYCoord();

    if (paddle_y >= 0 || (paddle_y + paddle.getBoundingClientRect().height <= (0 + (maxY - minY)))) {
        paddle.style.top = paddle_y + n + "px";
    }
    logPaddleYCoord();
}
function movePaddleToNpx(n_px) {
    paddle.style.top = n_px + "px";
    logPaddleYCoord();
}
function logPaddleYCoord() {
    var msgEl = document.getElementById("messages");
    msgEl.innerHTML = "Paddle: [y: " + getPaddleYCoord() + "px]";
}
function updateStrikes(score) {
    var strikeEle = document.getElementById("strikes");
    strikeEle.innerHTML = score.toString();
}
function updateMaxScore(score) {
    var maxScoreEle = document.getElementById("score");
    maxScoreEle.innerHTML = score.toString();
}
function updateScoresBeforeReset() {
    if (max_score < strikes) {
        max_score = strikes;
    }
    strikes = 0;
    updateStrikes(strikes);
    updateMaxScore(max_score);
}