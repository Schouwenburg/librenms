<?php
/**
 * Iosxe.php
 *
 * Cisco IOS-XE Wireless LAN Controller
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link       http://librenms.org
 * @copyright  2017 Tony Murray
 * @author     Tony Murray <murraytony@gmail.com>
 */

namespace LibreNMS\OS;

use LibreNMS\Interfaces\Discovery\Sensors\WirelessRssiDiscovery;
use LibreNMS\Interfaces\Discovery\Sensors\WirelessSnrDiscovery;
use LibreNMS\Interfaces\Discovery\Sensors\WirelessRsrqDiscovery;
use LibreNMS\Interfaces\Discovery\Sensors\WirelessRsrpDiscovery;
use LibreNMS\Interfaces\Discovery\Sensors\WirelessCellBandDiscovery;
use LibreNMS\Interfaces\Discovery\Sensors\WirelessCellIdDiscovery;
use LibreNMS\Interfaces\Discovery\Sensors\WirelessCcrxDiscovery;
use LibreNMS\OS\Traits\CiscoCellular;

class Iosxe extends Ciscowlc implements
    WirelessRssiDiscovery,
    WirelessRsrqDiscovery,
    WirelessRsrpDiscovery,
    WirelessCellBandDiscovery,
    WirelessCellIdDiscovery,
    WirelessSnrDiscovery,
    WirelessCcrxDiscovery
    {
        use CiscoCellular;
    }
