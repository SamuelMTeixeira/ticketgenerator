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


define('PLUGIN_TICKETGENERATOR_VERSION', '0.0.1');
define('PLUGIN_TICKETGENERATOR_MIN_GLPI', '10.0.0');
define('PLUGIN_TICKETGENERATOR_MAX_GLPI', '10.99.99');

function plugin_init_ticketgenerator()
{
  global $PLUGIN_HOOKS, $CFG_GLPI, $LANG;
  $PLUGIN_HOOKS['csrf_compliant']['ticketgenerator'] = true;

  Plugin::registerClass('PluginTicketgeneratorConfig', ['addtabon' => ['Ticket']]);

  $_SESSION["glpi_plugin_ticketgenerator_profile"]['ticketgenerator'] = 'w';
}

$PLUGIN_HOOKS['change_profile']['ticketgenerator'] = 'plugin_change_profile_ticketgenerator';

function plugin_version_ticketgenerator()
{
  return [
    'name'          => 'Gerador de Etiquetas',
    'version'       => PLUGIN_TICKETGENERATOR_VERSION,
    'author'        => 'Samuel Molendolff',
    'license'       => 'AGPLv3+',
    'homepage'      => 'https://github.com/SamuelMTeixeira',
    'requirements'  => [
      'glpi'  => [
        'min' => PLUGIN_TICKETGENERATOR_MIN_GLPI,
        'max' => PLUGIN_TICKETGENERATOR_MAX_GLPI,
      ]
    ]
  ];
}
