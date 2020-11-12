<style>

body {
  background-color: black;
}

.button {
  font-family: "Arial Black";
  display: block;
  border-radius: 4px;
  background-color: powderblue;
  border: solid;
  border-color: white;
  color: black;
  text-align: center;
  font-size: 15px;
  padding: 20px;
  width: 200px;
  transition: all 0.5s;
  cursor: pointer;
  margin: 5px;
}

.button span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  transition: 0.5s;
}

.button span:after {
  content: '\00bb';
  position: absolute;
  opacity: 0;
  top: 0;
  right: -20px;
  transition: 0.5s;
}

.button:hover span {
  padding-right: 25px;
}

.button:hover span:after {
  opacity: 1;
  right: 0;
}

input[type=submit] {
  background-color: powderblue;
  border-style: solid;
  border-color: white;
  color: black;
  padding: 5px 15px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
  font-family: Arial;
  font-weight: bold;
}

h1, h2, h3, h4, h5, label {
  font-family: Arial;
  color: white;
}

table, td {
  font-family: Arial;
  border: 3px solid black;
  border-collapse: collapse;
  background-color: white;
  text-align: center;
}

th {
  font-family: "Arial Black";
  border: 3px solid black;
  border-collapse: collapse;
  background-color: powderblue;
}

.home {
    position: fixed;
    bottom: 0px;
    right: 0px;
}

</style>
