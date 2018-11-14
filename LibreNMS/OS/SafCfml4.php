<?php
/**
 * SafCfml4.php
 *
 * Saf CFM L4 wireless radios
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
 * @package    LibreNMS
 * @link       http://librenms.org
 * @copyright  2018 Janno Schouwenburg
 * @author     Janno Schouwenburg <handel@janno.nl>
 */

namespace LibreNMS\OS;

use LibreNMS\Device\WirelessSensor;
use LibreNMS\Interfaces\Discovery\Sensors\WirelessFrequencyDiscovery;
use LibreNMS\Interfaces\Discovery\Sensors\WirelessMseDiscovery;
use LibreNMS\Interfaces\Discovery\Sensors\WirelessPowerDiscovery;
use LibreNMS\Interfaces\Discovery\Sensors\WirelessQualityDiscovery;
use LibreNMS\Interfaces\Discovery\Sensors\WirelessErrorsDiscovery;
use LibreNMS\OS;

class SafCfml4 extends OS implements
    WirelessFrequencyDiscovery,
    WirelessPowerDiscovery,
    WirelessErrorsDiscovery
{
    /**
     * Discover wireless frequency.  This is in MHz. Type is frequency.
     * Returns an array of LibreNMS\Device\Sensor objects that have been discovered
     *
     * @return array Sensors
     */
    public function discoverWirelessFrequency()
    {
        return array(
            // SAF-MPMMUX-MIB::cfml4radioTxFrequency
            new WirelessSensor(
                'frequency',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.7571.100.1.1.2.22.3.6.0',
                'saf-cfml4-tx',
                'cfml4radioR1TxFrequency',
                'Radio 1 Tx Frequency',
                null,
                1,
                1
            ),
            // SAF-MPMMUX-MIB::cfml4radioRxFrequency
            new WirelessSensor(
                'frequency',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.7571.100.1.1.2.22.3.7.0',
                'saf-cfml4-rx',
                'cfml4radioR1RxFrequency',
                'Radio 1 Rx Frequency',
                null,
                1,
                1
            ),
            new WirelessSensor(
                'frequency',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.7571.100.1.1.2.22.4.6.0',
                'saf-cfml4-tx',
                'cfml4radioR2TxFrequency',
                'Radio 2 Tx Frequency',
                null,
                1,
                1
            ),
            // SAF-MPMMUX-MIB::cfml4radioRxFrequency
            new WirelessSensor(
                'frequency',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.7571.100.1.1.2.22.4.7.0',
                'saf-cfml4-rx',
                'cfml4radioR2RxFrequency',
                'Radio 2 Rx Frequency',
                null,
                1,
                1
            ),
        );
    }

    /**
     * Discover wireless tx or rx power. This is in dBm. Type is power.
     * Returns an array of LibreNMS\Device\Sensor objects that have been discovered
     *
     * @return array
     */
    public function discoverWirelessPower()
    {
        return array(
            // SAF-MPMMUX-MIB::rf1TxLevel
            new WirelessSensor(
                'power',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.7571.100.1.1.2.22.3.8.0',
                'saf-cfml4-tx-power',
                'cfml4radioR1TxPower',
                'Radio 1 Tx Power'
            ),
            // SAF-MPMMUX-MIB::rf1RxLevel
            new WirelessSensor(
                'power',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.7571.100.1.1.2.22.3.10.0',
                'saf-cfml4-rx-level',
                'cfml4radioR1RxLevel',
                'Radio 1 Rx Level'
            ),
            // SAF-MPMMUX-MIB::rf2TxLevel
            new WirelessSensor(
                'power',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.7571.100.1.1.2.22.4.8.0',
                'saf-cfml4-tx-power',
                'cfml4radioR2TxPower',
                'Radio 2 Tx Power'
            ),
            // SAF-MPMMUX-MIB::rf2RxLevel
            new WirelessSensor(
                'power',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.7571.100.1.1.2.22.4.10.0',
                'saf-cfml4-rx-level',
                'cfml4radioR2RxLevel',
                'Radio 2 Rx Level'
            ),
        );
    }

    /**
     * Discover wireless tx or rx power. This is in dBm. Type is power.
     * Returns an array of LibreNMS\Device\Sensor objects that have been discovered
     *
     * @return array
     */
    public function discoverWirelessErrors()
    {
        return array(
            // SAF-MPMMUX-MIB::termFrameErrors
            new WirelessSensor(
                'errors',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.7571.100.1.1.2.22.1.10.0',
                'saf-cfml4',
                'cfml4termFrameErrors',
                'Frame errors'
            ),
            // SAF-MPMMUX-MIB::termBFrameErr
            new WirelessSensor(
                'errors',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.7571.100.1.1.2.22.1.29.0',
                'saf-cfml4',
                'cfml4termBFrameErr',
                'Background Frame errors'
            ),
        );
    }
}
