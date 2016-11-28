
$(document).ready(function(){
    
    $("#main").hide(); //hide navigation if user is not logged in.
    
    //login
    $("#login").on('click', function(event){
        
        event.preventDefault();
        
        var username = $("#username").val();
        var pass = $("#pass").val();
        
        var display = "Username="+name+"&pass="+pass;
        
        var lxmlhttp = new XMLHttpRequest();
        
        lxmlhttp.onreadystatechange = function() {
            if (this.readyState == 4){
                    if (this.status == 200) {
                        //make main visible if user is logged in
                        if (lxmlhttp.responseText == "User found"){
                            $("#main").show();
                            $("#main1").load("home.html");
                            getMsg();
                        }
                        else{
                            $("#status").text("User not found! Check login information!");
                        }
                    }
                    else{
                        $("#status").text("Error!");
                    }
            }
        };
        
        lxmlhttp.open("POST", "cheapomail.php", true);
        lxmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        lxmlhttp.send(display);
    });
    
    //Nagivate using AJAX
    $("#main ul li a").on('click', function(event){
        
        var logout = function(){
            var xxmlhttp = new XMLHttpRequest();
            
            var display ="logout=true";
        
            xxmlhttp.onreadystatechange = function() {
                if (this.readyState == 4){
                    if (this.status == 200) {
                        window.location.href = "/";
                    }
                }
            };
        
            xxmlhttp.open("POST", "cheapomail.php", true);
            xxmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xxmlhttp.send(display);
        }
        
        event.preventDefault();
        var page = $(this).attr("href");
        
        if (page == "index.html"){
            logout();
        }
        
        else if(page == "home.html"){
            $("#main1").load(page);
            getMsg();
        }
        else{
            $("#main1").load(page);
        }
    });
    
    function getMsg(){
        //handle getting messages
        var link = 'cheapomail.php?getMsg=true';
        
        $.ajax(link,{
            method: 'GET' 
        }).done(function(res){
            $("#msg").html(res);
            $('.recv').hide();
        
            $('.button1').on('click', function(){
                $(this).prev().slideToggle(400);
                readMsg($(this).parent(), $(this).next().text());
            });
            
        }).fail(function(){
            $("#msg").html("<p>Error!</p>");
        });
    }
    
    function readMsg(div, msgID){
        //handle reading message 
        
        var display = "readID="+msgID;
        var nxmlhttp = new XMLHttpRequest();
        
        nxmlhttp.onreadystatechange = function() {
            if (this.readyState == 4){
                if (this.status == 200) {
                    if (nxmlhttp.responseText == "Read"){
                        $(div).attr('class', 'msgread');
                    }
                }
            }
        };
        
        nxmlhttp.open("POST", "cheapomail.php", true);
        nxmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        nxmlhttp.send(display);
    }
});