var fs  = require('fs'),
server  = require('http').createServer(),
io                  = require('socket.io').listen(server),
mysql               = require('mysql'),
connectionsArray    = [],
connection          = mysql.createConnection({
                        host        : 'localhost',
                        user        : 'root',
                        password    : 'root',
                        database    : 'crewfac_development',
                        port        : 3306
                    }),
POLLING_INTERVAL = 15000,
pollingTimer;

server.listen(8002);
// If there is an error connecting to the database
connection.connect(function(err) {
	// connected! (unless `err` is set)
    console.log( err );
});

// on server ready we can load our client.html page
/*function handler ( req, res ) {
	fs.readFile( __dirname + '/client.html' , function ( err, data ) {
		if ( err ) {
			console.log( err );
			res.writeHead(500);
            return res.end( 'Error loading client.html' );
        }
        res.writeHead( 200 );
        res.end( data );
        console.log("Ujjval Herer"+data);
	});
}*/


/*
*
* HERE IT IS THE COOL PART
* This function loops on itself since there are sockets connected to the page
* sending the result of the database query after a constant interval
*
*/
var pollingLoop = function () {
    
    // Make the database query
	//var query = connection.query('SELECT C.id as `cid`,C.name,R.staff_id as `uid`,R.id as `rid` FROM `reservations` R, `clients` C, `user` U WHERE R.is_notified = 0 AND R.client_id = C.id AND (R.staff_id IS NOT NULL OR R.staff_id != 0) GROUP BY R.id');
    var query = connection.query('SELECT w.id,r.id as res_id,r.created_date,w.notification_type,w.notified,w.read,r.reservation_no,c.name,CONCAT_WS(" ",u.first_name, u.last_name) as full_name, u.id as uid from web_notification as w JOIN reservations r ON w.reservation_id = r.id JOIN clients c ON c.id = w.client_id JOIN user u ON u.id = w.assigned_to where w.read = 0');
    users = []; // this array will contain the result of our db query


    // set up the query listeners
    query
    .on('error', function(err) {
        // Handle error, and 'end' event will be emitted after this as well
        console.log( err );
        updateSockets( err );
        
    })
    .on('result', function( user ) {
        // it fills our array looping on each user row inside the db
        users.push( user );
    })
    .on('end',function(){
        // loop on itself only if there are sockets still connected
        if(connectionsArray.length) {
            pollingTimer = setTimeout( pollingLoop, POLLING_INTERVAL );

            updateSockets({users:users});
        }
    });

};

// create a new websocket connection to keep the content updated without any AJAX request
io.sockets.on( 'connection', function ( socket ) {
    
    console.log('Number of connections:' + connectionsArray.length);
    // start the polling loop only if at least there is one user connected
    if (!connectionsArray.length) {
        pollingLoop();
    }
    
    socket.on('disconnect', function () {
        var socketIndex = connectionsArray.indexOf( socket );
        console.log('socket = ' + socketIndex + ' disconnected');
        if (socketIndex >= 0) {
            connectionsArray.splice( socketIndex, 1 );
        }
    });

    console.log( 'A new socket is connected!' );
    connectionsArray.push( socket );
    
});

var updateSockets = function ( data ) {
    // store the time of the latest update
    data.time = new Date();
    // send new data to all the sockets connected
    connectionsArray.forEach(function( tmpSocket ){
        tmpSocket.volatile.emit( 'notification' , data );
    });
};