<?php
/**
 * CiscoCellular.php
 *
 * -Description-
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
 * @copyright  2019 Tony Murray
 * @author     Tony Murray <murraytony@gmail.com>
 */

namespace LibreNMS\OS\Traits;

use LibreNMS\Device\WirelessSensor;

trait CiscoCellular
{
    public function profileApn($index)
    {
        $cwceLteProfileApn = snmpwalk_cache_oid($this->getDeviceArray(), 'cwceLteProfileApn', [], 'CISCO-WAN-CELL-EXT-MIB');
        $apn = $cwceLteProfileApn[$index . '.1']['cwceLteProfileApn'];
        return $apn;
    }

    /**
     * Discover wireless RSSI (Received Signal Strength Indicator). This is in dBm. Type is rssi.
     * Returns an array of LibreNMS\Device\Sensor objects that have been discovered
     *
     * @return array
     */
    public function discoverWirelessRssi()
    {
        $sensors = [];

        $data = snmpwalk_cache_oid($this->getDeviceArray(), 'c3gCurrentGsmRssi', [], 'CISCO-WAN-3G-MIB');
        foreach ($data as $index => $entry) {
            $sensors[] = new WirelessSensor(
                'rssi',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.9.9.661.1.3.4.1.1.1.' . $index,
                'ios',
                $index,
                'RSSI: ' . $this->profileApn($index),
                $index,
                'RSSI: ' . $this->profileApn($index),
                //'RSSI: ' . str_replace('1.', '', $index),
                $entry['c3gCurrentGsmRssi.1']
            );
        }

        return $sensors;
    }

    /**
     * Discover wireless SNR (Signal-to-Noise Ratio). This is in dB. Type is snr.
     * Returns an array of LibreNMS\Device\Sensor objects that have been discovered
     *
     * @return array
     */
    public function discoverWirelessSnr()
    {
        $sensors = [];

        $data = snmpwalk_cache_oid($this->getDeviceArray(), 'cwceLteCurrSnr', [], 'CISCO-WAN-CELL-EXT-MIB');
        foreach ($data as $index => $entry) {
            $sensors[] = new WirelessSensor(
                'snr',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.9.9.817.1.1.1.1.1.3.' . $index,
                'ios',
                $index,
                'SNR: ' . $this->profileApn($index),
                $entry['cwceLteCurrSnr.1'],
                1,
                10
            );
        }

        return $sensors;
    }

    /**
     * Discover wireless RSRQ (Reference Signal Received Quality). This is in dB. Type is rsrq.
     * Returns an array of LibreNMS\Device\Sensor objects that have been discovered
     *
     * @return array
     */
    public function discoverWirelessRsrq()
    {
        $sensors = [];

        $data = snmpwalk_cache_oid($this->getDeviceArray(), 'cwceLteCurrRsrq', [], 'CISCO-WAN-CELL-EXT-MIB');
        foreach ($data as $index => $entry) {
            $sensors[] = new WirelessSensor(
                'rsrq',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.9.9.817.1.1.1.1.1.2.' . $index,
                'ios',
                $index,
                'RSRQ: ' . $this->profileApn($index),
                $index,
                'RSRQ: ' . $this->profileApn($index),
                $entry['cwceLteCurrRsrq.1'],
                1,
                10
            );
        }

        return $sensors;
    }

    /**
     * Discover wireless RSRP (Reference Signals Received Power). This is in dBm. Type is rsrp.
     * Returns an array of LibreNMS\Device\Sensor objects that have been discovered
     *
     * @return array
     */
    public function discoverWirelessRsrp()
    {
        $sensors = [];

        $data = snmpwalk_cache_oid($this->getDeviceArray(), 'cwceLteCurrRsrp', [], 'CISCO-WAN-CELL-EXT-MIB');
        foreach ($data as $index => $entry) {
            $sensors[] = new WirelessSensor(
                'rsrp',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.9.9.817.1.1.1.1.1.1.' . $index,
                'ios',
                $index,
                'RSRP: ' . $this->profileApn($index),
                $entry['cwceLteCurrRsrp.1']
            );
        }

        return $sensors;
    }

    /**
     * Discover wireless SINR (Signal-to-Interference-plus-Noise Ratio). This is in dB. Type is sinr.
     * Returns an array of LibreNMS\Device\Sensor objects that have been discovered
     *
     * @return array
     */
    public function discoverWirelessSinr()
    {
        $sensors = [];

        $data = snmpwalk_cache_oid($this->getDeviceArray(), 'cwceLteCurrSinr', [], 'CISCO-WAN-CELL-EXT-MIB');
        foreach ($data as $index => $entry) {
            $sensors[] = new WirelessSensor(
                'sinr',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.9.9.817.1.1.1.1.1.4.' . $index,
                'ios',
                $index,
                'SNIR: ' . $this->profileApn($index),
                $entry['cwceLteCurrSinr.1']
            );
        }

        return $sensors;
    }

    /**
     * Discover wireless Cellular Operating Band. This is in band number. Type is cellband.
     * Returns an array of LibreNMS\Device\Sensor objects that have been discovered
     *
     * @return array
     */
    public function discoverWirelessCellband()
    {
        $sensors = [];

        $data = snmpwalk_cache_oid($this->getDeviceArray(), 'cwceLteCurrOperatingBand', [], 'CISCO-WAN-CELL-EXT-MIB');
        foreach ($data as $index => $entry) {
            $sensors[] = new WirelessSensor(
                'cellband',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.9.9.817.1.1.1.1.1.6.' . $index,
                'ios',
                $index,
                'Cellular band: ' . $this->profileApn($index),
                $entry['cwceLteCurrOperatingBand.1']
            );
        }

        return $sensors;
    }

    /**
     * Discover wireless Cellular Cell Id. This is in cell number. Type is cellid.
     * Returns an array of LibreNMS\Device\Sensor objects that have been discovered
     *
     * @return array
     */
    public function discoverWirelessCellid()
    {
        $sensors = [];

        $data = snmpwalk_cache_oid($this->getDeviceArray(), 'c3gGsmCurrentCellId', [], 'CISCO-WAN-3G-MIB');
        foreach ($data as $index => $entry) {
            $sensors[] = new WirelessSensor(
                'cellid',
                $this->getDeviceId(),
                '.1.3.6.1.4.1.9.9.661.1.3.2.1.13.' . $index,
                'ios',
                $index,
                'Cellular Cell Id: ' . $this->profileApn($index),
                $entry['c3gGsmCurrentCellId.1']
            );
        }

        return $sensors;
    }
}
