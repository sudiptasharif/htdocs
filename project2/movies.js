/**
 * Name: Sudipta Sharif
 */
var movieSearchResultJSON;
var aMovieDetailsResultJSON;
var castDetailsResultJSON;
var imageURL;
var poster_path; 

var tblMovie = "F21_S001_20_MOVIE";
var tblMovieColumns = ["MOVIEID", "TITLE" , "OVERVIEW" , "BUDGET" , "REVENUE" , "RELEASEDATE" ,"RUNTIME" , "POSTERPATH"] 

var tblActor = "F21_S001_20_ACTOR";
var tblActorColumns = ["ACTORID", "ACTORNAME", "POSTERPATH"];

var tbleCast = "F21_S001_20_CAST";
var tbleCastColumns = ["MOVIEID", "ACTORID", "CHARNAME"];

var tblGenre = "F21_S001_20_GENRE";
var tbleGenreColumns = ["GENREID", "GENRENAME"];

var tbleMovieGenre = "F21_S001_20_MOVIE_GENRE";
var tbleMovieGenreColumns = ["MOVIEID", "GENREID"];

var totalCast = 50;
var gSelectedMovieID;
var gActorArray = [];
var gActorPosterArray = [];

function initialize() {
   movieSearchResultJSON = null;
   aMovieDetailsResultJSON = null;
   castDetailsResultJSON = null;
   imageURL = "http://image.tmdb.org/t/p/w185";
   clearMovieList();
   resetAllMovieDetails();
   setVisibility("movie_list_container", false);
   setVisibility("selected_movie_details_container", false);
}

function setPoster(posterEle, posterPath) {
   //l(posterEle);
   //l(posterPath);
   var imgEle = document.getElementById(posterEle);
   imgEle.setAttribute("src", posterPath);
   imgEle.style.visibility = "visible";
}

function setElementInnerHTML(ele, value) {
   var domEle = document.getElementById(ele);
   domEle.innerHTML = value;
}

function resetAllMovieDetails() {
   document.getElementById("movie_title").innerHTML = "";
   document.getElementById("movie_original_title").innerHTML = "";
   document.getElementById("movie_release_year").innerHTML = "";
   document.getElementById("movie_genres").innerHTML = "";
   document.getElementById("movie_overview").innerHTML = "";
   document.getElementById("cast_msg").innerHTML = "";
   for (var i = 0; i < totalCast; i++) {
      if (document.getElementById("char_actor" + (i + 1)) !== null)
         document.getElementById("char_actor" + (i + 1)).innerHTML = "";
      
      if (document.getElementById("name_actor" + (i + 1)) !== null)   
         document.getElementById("name_actor" + (i + 1)).innerHTML = "";
   }
   resetAllImgTags();
}

function resetAllImgTags() {
   document.getElementById("selected_movie_poster").src = "";
   document.getElementById("selected_movie_poster").style.visibility = "hidden";

   for(var i = 0; i < totalCast; i++) {
      if (document.getElementById("img_actor"+(i+1)) !== null)
         document.getElementById("img_actor"+(i+1)).src = "";
      if (document.getElementById("img_actor"+(i+1)) !== null)
         document.getElementById("img_actor"+(i+1)).style.visibility = "hidden";
   }
}

function setVisibility(ele_id_name, visible) {
   var ele = document.getElementById(ele_id_name);
   if (visible) {
      ele.style.visibility = "visible";
   } else {
      ele.style.visibility = "hidden";
   }
}

/************************************************************************************************************
 * Generate Movie List Functions
 *************************************************************************************************************/
function searchMoviesByTitle() {
   var movieSearchTitle = document.getElementById("form-input").value.trim();
   // reinitialize everything
   initialize();
   if (movieSearchTitle) {
      setWaitGUIVisibility(true);
      setVisibility("movie_list_container", true);
      sendMovieSearchRequest(movieSearchTitle, 1); // for the first search always use page = 1 (the first page only) 
   }
   setTimeout(createMovieListFromOtherPages, 3000);
}

function sendMovieSearchRequest(query, page) {
   var xhr = new XMLHttpRequest();
   query = encodeURI(query);
   page = encodeURI(page);

   var url = "proxy.php?method=/3/search/movie&query=" + query + "&page=" + page;
   xhr.open("GET", url);
   xhr.setRequestHeader("Accept", "application/json");

   xhr.onreadystatechange = function () {
      if (this.readyState == 4) {
         movieSearchResultJSON = JSON.parse(this.responseText);
        //l(movieSearchResultJSON);
         setTotalMovies(movieSearchResultJSON.total_results);
         createMovieList();
      }
   };
   xhr.send(null);
}

function createMovieList() {
   var movieList = movieSearchResultJSON.results;
   //l(movieList);
   var movieTitle = "", movieReleaseDate = "", movieReleaseYear = ""; movieID = "";

   for (var i = 0; i < movieList.length; i++) {
      movieTitle = movieList[i].title;
      movieReleaseDate = movieList[i].release_date;
      if (movieReleaseDate) {
         movieReleaseYear = getMovieReleaseYear(movieReleaseDate);
      } else {
         movieReleaseYear = "No Release Year From TMDb"
      }
      movieID = movieList[i].id;
      gSelectedMovieID = movieID;
      addNewMovieToList(movieTitle, movieReleaseYear, movieID);
      movieTitle = movieReleaseDate = movieReleaseYear = movieID = "";
   }
}

function createMovieListFromOtherPages() {
   setWaitGUIVisibility(true);
   var movieSearchTitle = document.getElementById("form-input").value.trim();
   var total_pages = movieSearchResultJSON.total_pages;
   var page_num = 2;
   for (page_num; page_num <= total_pages; page_num++) {
      sendMovieSearchRequest(movieSearchTitle, page_num);
   }
   setWaitGUIVisibility(false);
}

function getMovieReleaseYear(releaseDate) {
   return releaseDate.split("-")[0];
}

function setWaitGUIVisibility(visible) {
   var waitGUIEle = document.getElementById("wait_gui");
   if (visible) {
      waitGUIEle.style.visibility = "visible";
   } else {
      waitGUIEle.style.visibility = "hidden";
   }
}

function setTotalMovies(total) {
   var displayEle = document.getElementById("total_movies");
   displayEle.innerHTML = "(Total Found: " + total + ")";
   setVisibility("usage", parseInt(total) > 0);
}

function clearMovieList() {
   var movieOrderedListEle = document.getElementById("movie_ordered_list");
   var aMovieLiEle = movieOrderedListEle.firstElementChild;
   while (aMovieLiEle) {
      movieOrderedListEle.removeChild(aMovieLiEle);
      aMovieLiEle = movieOrderedListEle.firstElementChild;
   }
}

function clearActorList() {
   var castDetailsDiv = document.getElementById("selected_movie_cast_details");
   var anActorDiv = castDetailsDiv.firstElementChild;
   while(anActorDiv) {
      castDetailsDiv.removeChild(anActorDiv);
      anActorDiv = castDetailsDiv.firstElementChild;
   }
}

/************************************************************************************************************
 * Get Selected Movie Details Functions
 *************************************************************************************************************/
function getMovieDetails(movieID) {
   //l("List Clicked: Movie ID: " + movieID);
   // reset previous movie details if any
   gSelectedMovieID = movieID;
   resetAllMovieDetails();
   setVisibility("selected_movie_details_container", true);
   sendAMovieDetailsRequest(movieID);
}

function sendAMovieDetailsRequest(movieID) {
   var xhr = new XMLHttpRequest();
   movieID = encodeURI(movieID);

   var url = "proxy.php?method=/3/movie/" + movieID;
   xhr.open("GET", url);
   xhr.setRequestHeader("Accept", "application/json");

   xhr.onreadystatechange = function () {
      if (this.readyState == 4) {
         aMovieDetailsResultJSON = JSON.parse(this.responseText);
         //l(aMovieDetailsResultJSON);
         createASelectedMovieDetails(movieID);
         createMovieSQLQuery(aMovieDetailsResultJSON)
      }
   };
   xhr.send(null);
}

function createMovieSQLQuery(movieJSON) {
   var m = movieJSON;
   //l(m);
   l("Movie SQL");
   var sql = "INSERT INTO " + tblMovie + " (";
   for(var i = 0; i < tblMovieColumns.length; i++) {
      if(i === (tblMovieColumns.length - 1)) {
         sql += tblMovieColumns[i] + ") VALUES ("
      } else {
         sql += tblMovieColumns[i] + ", "
      }
   }

   sql += m.id + ", " + enquote(m.title) + ", " + enquote(m.overview) + ", " + m.budget +", " + m.revenue +", date " + enquote(m.release_date) + ", " + m.runtime + ", " + enquote(poster_path) +");" 
   l(sql);
   document.getElementById("sql1").innerHTML = sql;
   document.getElementById("sql1").innerHTML = document.getElementById("sql1").innerHTML + "<hr/>";
   
   l("Genre SQL");
   //l(m.genres);
   var movieGenreArr = m.genres;
   for(var i = 0; i < movieGenreArr.length; i++) {
      createGenreInsertSQL(movieGenreArr[i]);
   }
   document.getElementById("sql1").innerHTML = document.getElementById("sql1").innerHTML + "<hr/>";
   for(var i = 0; i < movieGenreArr.length; i++) {
      createMovieGenreInsertSQL(m.id, movieGenreArr[i].id);
   }   
   document.getElementById("sql1").innerHTML = document.getElementById("sql1").innerHTML + "<hr/>";
}

function createGenreInsertSQL(movieGenre) {
   var sql = "INSERT INTO " + tblGenre + " (";
   for(var i = 0; i < tbleGenreColumns.length; i++) {
      if(i === (tbleGenreColumns.length - 1)) {
         sql += tbleGenreColumns[i] + ") VALUES ("
      } else {
         sql += tbleGenreColumns[i] + ", "
      }
   }
   sql += movieGenre.id + ", " + enquote(movieGenre.name) + "); ";
   l(sql);
   document.getElementById("sql1").innerHTML = document.getElementById("sql1").innerHTML + "<br/>" + sql;
}

function createMovieGenreInsertSQL(movieID, movieGenreID) {
   var sql = "INSERT INTO " + tbleMovieGenre + " (";
   for(var i = 0; i < tbleMovieGenreColumns.length; i++) {
      if(i === (tbleMovieGenreColumns.length - 1)) {
         sql += tbleMovieGenreColumns[i] + ") VALUES ("
      } else {
         sql += tbleMovieGenreColumns[i] + ", "
      }
   }
   sql += movieID + ", " + movieGenreID + "); ";   
   l(sql);
   document.getElementById("sql1").innerHTML = document.getElementById("sql1").innerHTML + "<br/>" + sql;
}

function addActorForSQL(actorJSON, actorPosterPath, index) {
   gActorArray[index] = actorJSON;
   gActorPosterArray[index] = actorPosterPath;
}

function createActorAndCastSQLQuery() {
   //l(actorJSON);
   for (var i = 0; i < gActorArray.length; i++) {
      var actorJSON = gActorArray[i];
      var posterPath = gActorPosterArray[i];
      var sql = "INSERT INTO " + tblActor + " (";
      for(var j = 0; j < tblActorColumns.length; j++) {
         if(j === (tblActorColumns.length - 1)) {
            sql += tblActorColumns[j] + ") VALUES ("
         } else {
            sql += tblActorColumns[j] + ", "
         }
      }  
      sql += actorJSON.id + ", " + enquote(actorJSON.name) + ", " + enquote(posterPath) + ");";
      l(sql); 
      document.getElementById("sql1").innerHTML = document.getElementById("sql1").innerHTML + "<br/>" + sql;
   }
   document.getElementById("sql1").innerHTML = document.getElementById("sql1").innerHTML + "<hr/>";

   for (var i = 0; i < gActorArray.length; i++) {
      var actorJSON = gActorArray[i];
      var posterPath = gActorPosterArray[i];
      sql = "INSERT INTO " + tbleCast + " (";
      gSelectedMovieID
      for(var j = 0; j < tbleCastColumns.length; j++) {
         if(j === (tbleCastColumns.length - 1)) {
            sql += tbleCastColumns[j] + ") VALUES ("
         } else {
            sql += tbleCastColumns[j] + ", "
         }
      }
      sql += gSelectedMovieID + ", " + actorJSON.id + ", " + enquote(actorJSON.character) + ");";
      l(sql);
      document.getElementById("sql1").innerHTML = document.getElementById("sql1").innerHTML + "<br/>" + sql;
   }  
   document.getElementById("sql1").innerHTML = document.getElementById("sql1").innerHTML + "<hr/>";
}

function createASelectedMovieDetails(movieID) {
   var movie_title = "<b>Title: </b>",
      movie_overview = "<b>Overview: </b>", genreStr = "<b>Genre(s): </b>",
      release_date = "<b>Release Date: </b>", original_title = "<b>Original Title: </b>";
   var genreList;

   movie_title += aMovieDetailsResultJSON.title;
   original_title += aMovieDetailsResultJSON.original_title;
   movie_overview += aMovieDetailsResultJSON.overview;
   release_date += aMovieDetailsResultJSON.release_date;

   genreList = aMovieDetailsResultJSON.genres;
   for (var i = 0; i < genreList.length; i++) {
      if (i === genreList.length - 1) {
         genreStr += genreList[i].name + ".";
      } else {
         genreStr += genreList[i].name + ", ";
      }
   }
   if (aMovieDetailsResultJSON.poster_path) {
      poster_path = imageURL + aMovieDetailsResultJSON.poster_path;
   } else {
      poster_path = "no_poster.png";
   }
   //l(poster_path);
   setPoster("selected_movie_poster", poster_path);
   setElementInnerHTML("movie_title", movie_title);
   setElementInnerHTML("movie_original_title", original_title);
   setElementInnerHTML("movie_release_year", release_date);
   setElementInnerHTML("movie_genres", genreStr);
   setElementInnerHTML("movie_overview", movie_overview);

   sendCastDetailsRequest(movieID);
}
/************************************************************************************************************
 * Generate Top Actors
 *************************************************************************************************************/
function sendCastDetailsRequest(movieID) {
   var xhr = new XMLHttpRequest();
   movieID = encodeURI(movieID);

   var url = "proxy.php?method=/3/movie/" + movieID + "/credits";
   xhr.open("GET", url);
   xhr.setRequestHeader("Accept", "application/json");

   xhr.onreadystatechange = function () {
      if (this.readyState == 4) {
         castDetailsResultJSON = JSON.parse(this.responseText);
         createCastDetails();
      }
   };
   xhr.send(null);
}

function createCastDetails() {
   var castList = castDetailsResultJSON.cast;
   var totalCastToShow = totalCast; 
   if (castList.length < totalCast) {
      totalCastToShow = castList.length;
      setElementInnerHTML("cast_msg", "(" + totalCastToShow + " found!)")
   }
   clearActorList();
   createActorPosterDivList(totalCastToShow);
   l("Actor/Cast SQL");
   gActorArray = [];
   gActorPosterArray = [];
   for (var i = 0; i < totalCastToShow; i++) {
      addCastDetails(castList[i], i);
   }
   l(gActorArray);
   l(gActorPosterArray);
   createActorAndCastSQLQuery();
}
function addCastDetails(castJSON, index) {
   var char_html_ele = "char_actor" + (index+1);
   var actor_name_html_ele = "name_actor" + (index+1);
   var actor_img_html_ele = "img_actor" + (index+1);

   var characterName = "<b>Character Name: </b>" + castJSON.character;
   var actor_name = "<b>Actor Name: </b>" + castJSON.name;

   var actor_img_path = castJSON.profile_path;
   if (actor_img_path !== null) {
      actor_img_path = imageURL + actor_img_path;
   } else {
      actor_img_path = "no_actor.png";
   }
   setPoster(actor_img_html_ele, actor_img_path);
   setElementInnerHTML(char_html_ele, characterName);
   setElementInnerHTML(actor_name_html_ele, actor_name);
   addActorForSQL(castJSON, actor_img_path, index);
}

function enquote(val) {
   return "\'" + val.replace(/\'/g, "\'\'") + "\'";
}

function addNewMovieToList(title, year, id) {
   var onclicFunc = "getMovieDetails(" + id + ");";
   var unorderd_list = document.getElementById("movie_ordered_list");
   var list_node = document.createElement("li");
   list_node.setAttribute("id", id);
   list_node.innerHTML = title + " (" + year + ")";
   list_node.setAttribute("onclick", onclicFunc);
   unorderd_list.appendChild(list_node);
}

function createActorPosterDivList(divCount) {
   var castDetailsDiv = document.getElementById("selected_movie_cast_details");
   for(var i = 0; i < divCount; i++) {
      castDetailsDiv.appendChild(getActorPosterDiv(i+1));
   }
}

function getActorPosterDiv(index) {
   var divEle = document.createElement("div");
   divEle.setAttribute("id", "actor"+index);
   divEle.setAttribute("class", "actor");
   divEle.setAttribute("style", "float:left")

   var imgEle = document.createElement("img");
   imgEle.setAttribute("id", "img_actor"+index);
   imgEle.setAttribute("src", "");
   imgEle.setAttribute("alt", "actor_poster");

   var pCharActor = document.createElement("p");
   pCharActor.setAttribute("id", "char_actor"+index);

   var pNameActor = document.createElement("p");
   pNameActor.setAttribute("id", "name_actor"+index);

   divEle.appendChild(imgEle);
   divEle.appendChild(pCharActor);
   divEle.appendChild(pNameActor);

   return divEle;
}

function l(val) {
   console.log(val);
}