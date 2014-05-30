/*
SQL script to reset the matches and goals database to default settings
This will delete all the result data, including qualifiers plus all
goal data

Nick M 27/05/14

*/

use hurst; /* ensure this can't be used on live db */

/* remove all results and winners */
update matches set result = null, winner_id = null, winmethod = null;
/* for each ko match, restore the default values */ 
update matches set teama_id = '1A', teamb_id = '2B' where id = 49;
update matches set teama_id = '1C', teamb_id = '2D' where id = 50;
update matches set teama_id = '1B', teamb_id = '2A' where id = 51;
update matches set teama_id = '1D', teamb_id = '2C' where id = 52;
update matches set teama_id = '1E', teamb_id = '2F' where id = 53;
update matches set teama_id = '1G', teamb_id = '2H' where id = 54;
update matches set teama_id = '1F', teamb_id = '2E' where id = 55;
update matches set teama_id = '1H', teamb_id = '2G' where id = 56;
update matches set teama_id = 'W49', teamb_id = 'W50' where id = 57;
update matches set teama_id = 'W53' , teamb_id = 'W54' where id = 58;
update matches set teama_id = 'W51' , teamb_id = 'W52' where id = 59;
update matches set teama_id = 'W55' , teamb_id = 'W56' where id = 60;
update matches set teama_id = 'W57' , teamb_id = 'W58' where id = 61;
update matches set teama_id = 'W59' , teamb_id = 'W60' where id = 62;
update matches set teama_id = 'L61' , teamb_id = 'L62' where id = 63;
update matches set teama_id = 'W61' , teamb_id = 'W62' where id = 64;
/* remove all goal data (and reset the auto-increment id) */
truncate table goals;