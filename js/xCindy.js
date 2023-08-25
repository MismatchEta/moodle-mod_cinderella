"use strict";
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * JS functions for easier handling of xAPI statements from cinderella.
 * Requires adlnet/xapi-wrapper.
 * 
 * @package   mod_cinderella
 * @copyright 2023 Marcus Röhming <marcus.roehming@outlook.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Connects to a LRS.
 * 
 * @param string endpoint LRS endpoint URI to connect to
 * @param string user LRS username
 * @param string password LRS users password
 */
function connect_to_LRS(endpoint, user, password) {
    // LRS Configuration for xAPI wrapper.
    const conf = {
        'endpoint': endpoint,
        'auth': 'Basic ' + toBase64(user + ':' + password),
    };

    // Connecting to LRS.
    ADL.XAPIWrapper.changeConfig(conf);
}

/** 
 * Sends basic xAPI Statement to configured LRS.
 * 
 * @param string uName Moodle Username or other to be used as xAPI actor
 * @param string uEmail Mail to be used with xAPI actor
 * @param string verb verb to display
 * @param string verbID IRI of verb for reference in queries etc.
 * @param string object object to display (hard coded as activity)
 * @param string objectID IRI of object for reference in queries etc.
 * @param string typeID IRI of type in definition child.
 * @param string response for result object of statement.
 */
function sendStatement(uName, uEmail, verb, verbID, object, objectID, typeID, objDesc = "N/A", response = "N/A") {

    // Creating a XAPI-statement as JSON-object.
    const statement = {
        "actor": {
            "objectType": "Agent",
            "name": uName,
            "mbox": "mailto:" + uEmail
        },
        "verb": {
            "id": verbID,
            "display": {
                "en-us": verb
            }
        },
        "object": {
            "id": objectID,
            "definition": {
                "name": {"en-us": object},
                "description": {"en-us": objDesc},
                "type": typeID 
            },
            "objectType": "Activity"
        },
        "result": {
            "response": "" + response
        }
    };

    // Send the statement.
    const result = ADL.XAPIWrapper.sendStatement(statement);
}

/** 
 * Sends a query to LRS with details given by some textboxes.
 * @returns {object} queryData - Object of statement-Array from LRS (json)
 */
function queryLRS(agent, verb, activity) {
    
    // Define search parameters.
    // Note: You can query by agent, verb, activity or timestamp, basically the things that have to exist
    const parameters = ADL.XAPIWrapper.searchParams()
    parameters["agent"] = agent 
    parameters["verb"] = verb
    parameters["object"] = activity
    
    // Query LRS
    const queryData = ADL.XAPIWrapper.getStatements(parameters)
    
    // Populate unordered list with details from queryData
    document.getElementById("query-results").textContent = ""
    queryData.statements.forEach(printStatements)

    return queryData
}

function printStatements(statement) {
    const newEntry = document.createElement("li")
    const newStatement = document.createTextNode(statement.actor.name + " " + statement.verb.display["en-us"] + " " + statement.object.definition.name["en-us"] + " to coords: [" + statement.result.response + "]")
    newEntry.appendChild(newStatement) 
    document.getElementById("query-results").appendChild(newEntry)
}

function queryLRS_DEBUG(agent,verbID,objID) {
    
    console.log(">DEBUG FOR QUERY LRS")
    console.log(agent)
    console.log(verbID)
    console.log(objID)
    console.log("<DEBUG END")
}