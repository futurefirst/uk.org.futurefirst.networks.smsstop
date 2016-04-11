# SMS STOP/unsubscribe processor
An extension for CiviCRM.
Acts upon received SMS messages beginning with keywords such as STOP,
which indicate the remote contact's wish to unsubscribe. This extension
replies to the contact acknowledging their request, and sets them as
'do not SMS'. It is an attempt at following section 10.1.1
(STOP command implementation) of the Clickatell two-way technical guide.

### Installation
Install in the usual ways, either from the CiviCRM extensions repository or
by downloading directly from GitHub. If you download a snapshot you may need
to rename the extension's directory to the extension's key
(`uk.org.futurefirst.networks.smsstop`).

This extension *requires* CiviCooP's SMS API extension (`org.civicoop.smsapi`)
in order to send the acknowledgement SMS.

### Features
* Acknowledge unsubscribe requests with a custom reply- you can, for example,
write an apology that includes a number to call for further assistance
* Automatically set the contact to 'do not SMS', so CiviCRM knows not to
attempt to send to them in future
* The above should co-operate with the SMS Survey extension if used,
stopping the next question from being sent to that contact

### Links
* <http://www.futurefirst.org.uk/>
* <https://github.com/futurefirst/uk.org.futurefirst.networks.smsstop/wiki>
* <https://github.com/CiviCooP/org.civicoop.smsapi>
* <https://github.com/futurefirst/org.thirdsectordesign.chainsms>

### Licensing
Copyright (C)2016 [Future First](http://www.futurefirst.org.uk/).
Future First Alumni Limited (formerly known as The Camden Future First Network
Limited), operating under the public name 'Future First', is a registered
charity in England and Wales (1135638), and in Scotland (SC043973).

Maintained by [David Knoll](mailto:david@futurefirst.org.uk).

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
