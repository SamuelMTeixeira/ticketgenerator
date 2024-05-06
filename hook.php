<?php
/*
   ------------------------------------------------------------------------
   Ticket Generator
   Copyright (C) 2016-2024 by Samuel Molendolff
   https://github.com/SamuelMTeixeira
   ------------------------------------------------------------------------
   LICENSE
   This file is part of Ticket Generator project.
   Ticket Generator is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.
   Ticket Generator is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.
   You should have received a copy of the GNU Affero General Public License
   along with Ticket Generator. If not, see <http://www.gnu.org/licenses/>.
   ------------------------------------------------------------------------
   @package   TicketGenerator
   @author    Samuel Molendolff Teixeira
   @copyright Copyright (c) 2024 SamuelMTeixeira
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://github.com/SamuelMTeixeira
   @since     2024
   ------------------------------------------------------------------------
 */
function plugin_ticketgenerator_install() {
  
  global $DB, $LANG;
  
  function plugin_change_profile_ticketgenerator() {
    if (Session::haveRight('config', UPDATE)) {
       $_SESSION["glpi_plugin_ticketgenerator_profile"] = ['ticketgenerator' => 'w'];
 
    } else if (Session::haveRight('config', READ)) {
       $_SESSION["glpi_plugin_ticketgenerator_profile"] = ['ticketgenerator' => 'r'];
 
    } else {
       unset($_SESSION["glpi_plugin_ticketgenerator_profile"]);
    }
 }
 
  return true;
}
function plugin_ticketgenerator_uninstall(){
  return true;
}