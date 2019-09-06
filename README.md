# geojsonMapsAPI
Simple use cases of Maps API on the Data Layer with GeoJSON data

## !! When testing the project, please replace the Google Maps API Key. !!

##Few notes on the project:
1. This is a completely new project for me as I have only used Maps API before but not anything to do with GeoJSON and the data layer. I have focused on functionality than the looks of the project due to time constraints.
2. I am using a geojson file that contains Dublin's Parking data from SmartDublin website and built code in a way that the properties matches this data so it will not be compatible for all geojson file.
3. I could have used the Maps API to find the address from the coordinates when generating table but I am using a live API(Restricted to certain IPs) active on one of my projects. Each call would have been a cost and with 2000+ data points on my test geojson file, I used Location property of the geojson file instead.

##What the application does:
1. Load a geoJSON file to view the points on the Map.
2. Map is focused towards Dublin, change it as necessary.
3. Use the Lasso Tool to place markers on the map. The find button will be activated once the polygon is completed.
4. The details of the points within that perimeter would be display when you click the find button.

##Useful URL:

containsLocation : https://developers.google.com/maps/documentation/javascript/examples/poly-containsLocation
