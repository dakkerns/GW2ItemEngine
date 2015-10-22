# GW2ItemEngine

Guild Wars 2 item search engine that uses GW2Spidy's API for market data and ArenaNet's own API for general item information.

Every 2 hours, a cron job is run updating the current market asking price, offer price, supply, and demand. This data is also aggregated 
in a table that has stored market information for all items since Sept 30, 2015. Upon clicking on a desired item,a Highcharts graph is shown 
with its respective sales trends, as well as basic item information in the form of a custom tooltip similar to those in-game.

The search table is also pre-populated on each visit with your most recent searches, via a cookie. 

That's really all the basic functionality of the site at the moment.


