<?php
/**
 * entity-physical.inc.php
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

echo "\nCaching OIDs:";

if ($device['os'] == 'junos') {
    $entity_array = array();
    echo ' jnxBoxAnatomy';
    $entity_array = snmpwalk_cache_oid($device, 'jnxBoxAnatomy', $entity_array, 'JUNIPER-MIB');
} elseif ($device['os'] == 'timos') {
    $entity_array = array();
    echo 'tmnxHwObjs';
    $entity_array = snmpwalk_cache_multi_oid($device, 'tmnxHwObjs', $entity_array, 'TIMETRA-CHASSIS-MIB', '+'.$config['mib_dir'].'/aos:'.$config['mib_dir']);
} else {
    $entity_array = array();
    echo ' entPhysicalEntry';
    $entity_array = snmpwalk_cache_oid($device, 'entPhysicalEntry', $entity_array, 'ENTITY-MIB:CISCO-ENTITY-VENDORTYPE-OID-MIB');

    if (!empty($entity_array)) {
        echo ' entAliasMappingIdentifier';
        $entity_array = snmpwalk_cache_twopart_oid($device, 'entAliasMappingIdentifier', $entity_array, 'ENTITY-MIB:IF-MIB');
    }
    echo json_encode($entity_array);
}
if ($device['os'] == 'vrp') {
    echo ' hwEntityBoardType';
    $entity_array = snmpwalk_cache_oid($device, 'hwEntityBoardType', $entity_array, 'ENTITY-MIB:HUAWEI-ENTITY-EXTENT-MIB');
    echo ' hwEntityBomEnDesc';
    $entity_array = snmpwalk_cache_oid($device, 'hwEntityBomEnDesc', $entity_array, 'ENTITY-MIB:HUAWEI-ENTITY-EXTENT-MIB');
}
if ($device['os'] == 'saf-cfml4') {
    $entity_array = array();
    $device_array = array();
    echo ' saf-cfml4Anatomy';
    $oid = '.1.3.6.1.4.1.7571.100.1.1.2.22';
    $row = 0;
    $device_array = snmpwalk_cache_oid($device, $oid, $entity_array, 'SAF-MPMMUX-MIB');
    $oid = '3.6.1.4.1.7571.100.1.1.2.22';
    // Descr, VendorType, ContainedIn, Class, ParentRelPos,
    //  Name, HardwareRev, FirmwareRev, SoftwareRev, SerialNum, MfgName, ModelName, Alias, Alias, AssetID, IsFRU
    $entity_array[++$row] = return_entity_array('CFM L4', 'CFM L4', '0', 'chassis', '-1',
        'Chassis', '', '', '', $device_array[$oid.'.1.13.0']['iso'], 'SAF', 'CFM L4', '', '', 'true');
    $entity_array[++$row] = return_entity_array($device_array[$oid.'.3.3.0']['iso'], 'radio', '1', 'module', '1',
        'Radio 1', '', '', '', '', '', '', '', '', 'true' );
    $entity_array[++$row] = return_entity_array($device_array[$oid.'.4.3.0']['iso'], 'radio', '1', 'module', '2',
        'Radio 2', '', '', '', '', '', '', '', '', 'true' );
    $entity_array[++$row] = return_entity_array('Module Container', 'containerSlot', '1', 'container', '3',
        'Slot 1', '', '', '', '', '', '', '', '', 'false');
    $entity_array[++$row] = return_entity_array('Module Container', 'containerSlot', '1', 'container', '4',
        'Slot 2', '', '', '', '', '', '', '', '', 'false');
    $entity_array[++$row] = return_entity_array('Module Container', 'containerSlot', '1', 'container', '5',
        'Slot 3', '', '', '', '', '', '', '', '', 'false');
    $entity_array[++$row] = return_entity_array('Module Container', 'containerSlot', '1', 'container', '6',
        'Slot 4', '', '', '', '', '', '', '', '', 'false');
    if (!preg_match('/N\/A/', $device_array[$oid.'.6.1.2.0']['iso'])) {
        $entity_array[++$row] = return_entity_array($device_array[$oid.'.6.1.2.0']['iso'], 'module', '4', 'module', '1',
            'Module 1', '', '', '', '', '', '', '', '', 'true');
    }
    if (!preg_match('/N\/A/', $device_array[$oid.'.6.2.2.0']['iso'])) {
        $entity_array[++$row] = return_entity_array($device_array[$oid.'.6.2.2.0']['iso'], 'module', '5', 'module', '1',
            'Module 2', '', '', '', '', '', '', '', '', 'true');
    }
    if (!preg_match('/N\/A/', $device_array[$oid.'.6.3.2.0']['iso'])) {
        $entity_array[++$row] = return_entity_array($device_array[$oid.'.6.3.2.0']['iso'], 'module', '6', 'module', '1',
            'Module 3', '', '', '', '', '', '', '', '', 'true');
    }
    if (!preg_match('/N\/A/', $device_array[$oid.'.6.4.2.0']['iso'])) {
        $entity_array[++$row] = return_entity_array($device_array[$oid.'.6.4.2.0']['iso'], 'module', '7', 'module', '1',
            'Module 4', '', '', '', '', '', '', '', '', 'true');
    }
}

foreach ($entity_array as $entPhysicalIndex => $entry) {
    if ($device['os'] == 'junos') {
        // Juniper's MIB doesn't have the same objects as the Entity MIB, so some values
        // are made up here.
        $entPhysicalDescr        = $entry['jnxContentsDescr'];
        $entPhysicalContainedIn  = $entry['jnxContainersWithin'];
        $entPhysicalClass        = $entry['jnxBoxClass'];
        $entPhysicalName         = $entry['jnxOperatingDescr'];
        $entPhysicalSerialNum    = $entry['jnxContentsSerialNo'];
        $entPhysicalModelName    = $entry['jnxContentsPartNo'];
        $entPhysicalMfgName      = 'Juniper';
        $entPhysicalVendorType   = 'Juniper';
        $entPhysicalParentRelPos = -1;
        $entPhysicalHardwareRev  = $entry['jnxContentsRevision'];
        $entPhysicalFirmwareRev  = $entry['entPhysicalFirmwareRev'];
        $entPhysicalSoftwareRev  = $entry['entPhysicalSoftwareRev'];
        $entPhysicalIsFRU        = $entry['jnxFruType'];
        $entPhysicalAlias        = $entry['entPhysicalAlias'];
        $entPhysicalAssetID      = $entry['entPhysicalAssetID'];
        // fix for issue 1865, $entPhysicalIndex, as it contains a quad dotted number on newer Junipers
        // using str_replace to remove all dots should fix this even if it changes in future
        $entPhysicalIndex = str_replace('.', '', $entPhysicalIndex);
    } elseif ($device['os'] == 'timos') {
        $entPhysicalDescr        = $entry['tmnxCardTypeDescription'];
        $entPhysicalContainedIn  = $entry['tmnxHwContainedIn'];
        $entPhysicalClass        = $entry['tmnxHwClass'];
        $entPhysicalName         = $entry['tmnxCardTypeName'];
        $entPhysicalSerialNum    = $entry['tmnxHwSerialNumber'];
        $entPhysicalModelName    = $entry['tmnxHwMfgBoardNumber'];
        $entPhysicalMfgName      = $entry['tmnxHwMfgBoardNumber'];
        $entPhysicalVendorType   = $entry['tmnxCardTypeName'];
        $entPhysicalParentRelPos = $entry['tmnxHwParentRelPos'];
        $entPhysicalHardwareRev  = '1.0';
        $entPhysicalFirmwareRev  = $entry['tmnxHwBootCodeVersion'];
        $entPhysicalSoftwareRev  = $entry['tmnxHwBootCodeVersion'];
        $entPhysicalIsFRU        = $entry['tmnxHwIsFRU'];
        $entPhysicalAlias        = $entry['tmnxHwAlias'];
        $entPhysicalAssetID      = $entry['tmnxHwAssetID'];
        $entPhysicalIndex = str_replace('.', '', $entPhysicalIndex);
    } elseif ($device['os'] == 'vrp') {
        //Add some details collected in the VRP Entity Mib
        $entPhysicalDescr        = $entry['hwEntityBomEnDesc'];
        $entPhysicalContainedIn  = $entry['entPhysicalContainedIn'];
        $entPhysicalClass        = $entry['entPhysicalClass'];
        $entPhysicalName         = $entry['entPhysicalName'];
        $entPhysicalSerialNum    = $entry['entPhysicalSerialNum'];
        $entPhysicalModelName    = $entry['hwEntityBoardType'];
        $entPhysicalMfgName      = $entry['entPhysicalMfgName'];
        $entPhysicalVendorType   = $entry['entPhysicalVendorType'];
        $entPhysicalParentRelPos = $entry['entPhysicalParentRelPos'];
        $entPhysicalHardwareRev  = $entry['entPhysicalHardwareRev'];
        $entPhysicalFirmwareRev  = $entry['entPhysicalFirmwareRev'];
        $entPhysicalSoftwareRev  = $entry['entPhysicalSoftwareRev'];
        $entPhysicalIsFRU        = $entry['entPhysicalIsFRU'];
        $entPhysicalAlias        = $entry['entPhysicalAlias'];
        $entPhysicalAssetID      = $entry['entPhysicalAssetID'];
    } else {
        $entPhysicalDescr        = $entry['entPhysicalDescr'];
        $entPhysicalContainedIn  = $entry['entPhysicalContainedIn'];
        $entPhysicalClass        = $entry['entPhysicalClass'];
        $entPhysicalName         = $entry['entPhysicalName'];
        $entPhysicalSerialNum    = $entry['entPhysicalSerialNum'];
        $entPhysicalModelName    = $entry['entPhysicalModelName'];
        $entPhysicalMfgName      = $entry['entPhysicalMfgName'];
        $entPhysicalVendorType   = $entry['entPhysicalVendorType'];
        $entPhysicalParentRelPos = $entry['entPhysicalParentRelPos'];
        $entPhysicalHardwareRev  = $entry['entPhysicalHardwareRev'];
        $entPhysicalFirmwareRev  = $entry['entPhysicalFirmwareRev'];
        $entPhysicalSoftwareRev  = $entry['entPhysicalSoftwareRev'];
        $entPhysicalIsFRU        = $entry['entPhysicalIsFRU'];
        $entPhysicalAlias        = $entry['entPhysicalAlias'];
        $entPhysicalAssetID      = $entry['entPhysicalAssetID'];
    }//end if

    if (isset($entity_array[$entPhysicalIndex]['0']['entAliasMappingIdentifier'])) {
        $ifIndex = $entity_array[$entPhysicalIndex]['0']['entAliasMappingIdentifier'];
    }

    if (!strpos($ifIndex, 'fIndex') || $ifIndex == '') {
        unset($ifIndex);
    } else {
        $ifIndex_array = explode('.', $ifIndex);
        $ifIndex       = $ifIndex_array[1];
        unset($ifIndex_array);
    }

    // List of real names for cisco entities
    $entPhysicalVendorTypes = array(
        'cevC7xxxIo1feTxIsl'   => 'C7200-IO-FE-MII',
        'cevChassis7140Dualfe' => 'C7140-2FE',
        'cevChassis7204'       => 'C7204',
        'cevChassis7204Vxr'    => 'C7204VXR',
        'cevChassis7206'       => 'C7206',
        'cevChassis7206Vxr'    => 'C7206VXR',
        'cevCpu7200Npe200'     => 'NPE-200',
        'cevCpu7200Npe225'     => 'NPE-225',
        'cevCpu7200Npe300'     => 'NPE-300',
        'cevCpu7200Npe400'     => 'NPE-400',
        'cevCpu7200Npeg1'      => 'NPE-G1',
        'cevCpu7200Npeg2'      => 'NPE-G2',
        'cevPa1feTxIsl'        => 'PA-FE-TX-ISL',
        'cevPa2feTxI82543'     => 'PA-2FE-TX',
        'cevPa8e'              => 'PA-8E',
        'cevPaA8tX21'          => 'PA-8T-X21',
        'cevMGBIC1000BaseLX'   => '1000BaseLX GBIC',
        'cevPort10GigBaseLR'   => '10GigBaseLR',
    );

    if ($entPhysicalVendorTypes[$entPhysicalVendorType] && !$entPhysicalModelName) {
        $entPhysicalModelName = $entPhysicalVendorTypes[$entPhysicalVendorType];
    }

    // FIXME - dbFacile
    if ($entPhysicalDescr || $entPhysicalName) {
        $entPhysical_id = dbFetchCell('SELECT entPhysical_id FROM `entPhysical` WHERE device_id = ? AND entPhysicalIndex = ?', array($device['device_id'], $entPhysicalIndex));

        if ($entPhysical_id) {
            $update_data = array(
                'entPhysicalIndex'        => $entPhysicalIndex,
                'entPhysicalDescr'        => $entPhysicalDescr,
                'entPhysicalClass'        => $entPhysicalClass,
                'entPhysicalName'         => $entPhysicalName,
                'entPhysicalModelName'    => $entPhysicalModelName,
                'entPhysicalSerialNum'    => $entPhysicalSerialNum,
                'entPhysicalContainedIn'  => $entPhysicalContainedIn,
                'entPhysicalMfgName'      => $entPhysicalMfgName,
                'entPhysicalParentRelPos' => $entPhysicalParentRelPos,
                'entPhysicalVendorType'   => $entPhysicalVendorType,
                'entPhysicalHardwareRev'  => $entPhysicalHardwareRev,
                'entPhysicalFirmwareRev'  => $entPhysicalFirmwareRev,
                'entPhysicalSoftwareRev'  => $entPhysicalSoftwareRev,
                'entPhysicalIsFRU'        => $entPhysicalIsFRU,
                'entPhysicalAlias'        => $entPhysicalAlias,
                'entPhysicalAssetID'      => $entPhysicalAssetID,
            );
            dbUpdate($update_data, 'entPhysical', 'device_id=? AND entPhysicalIndex=?', array($device['device_id'], $entPhysicalIndex));
            echo '.';
        } else {
            $insert_data = array(
                'device_id'               => $device['device_id'],
                'entPhysicalIndex'        => $entPhysicalIndex,
                'entPhysicalDescr'        => $entPhysicalDescr,
                'entPhysicalClass'        => $entPhysicalClass,
                'entPhysicalName'         => $entPhysicalName,
                'entPhysicalModelName'    => $entPhysicalModelName,
                'entPhysicalSerialNum'    => $entPhysicalSerialNum,
                'entPhysicalContainedIn'  => $entPhysicalContainedIn,
                'entPhysicalMfgName'      => $entPhysicalMfgName,
                'entPhysicalParentRelPos' => $entPhysicalParentRelPos,
                'entPhysicalVendorType'   => $entPhysicalVendorType,
                'entPhysicalHardwareRev'  => $entPhysicalHardwareRev,
                'entPhysicalFirmwareRev'  => $entPhysicalFirmwareRev,
                'entPhysicalSoftwareRev'  => $entPhysicalSoftwareRev,
                'entPhysicalIsFRU'        => $entPhysicalIsFRU,
                'entPhysicalAlias'        => $entPhysicalAlias,
                'entPhysicalAssetID'      => $entPhysicalAssetID,
            );

            if (!empty($ifIndex)) {
                $insert_data['ifIndex'] = $ifIndex;
            }

            dbInsert($insert_data, 'entPhysical');
            echo '+';
        }//end if

        $valid[$entPhysicalIndex] = 1;
    }//end if
}//end foreach
echo "\n";
unset(
    $update_data,
    $insert_data,
    $entry,
    $entity_array
);

// Function which returns an array in Entity format based on the supplied input variables
function return_entity_array($descr, $vendortype, $containedin, $class, $parentrelpos,
     $name, $hardwarerev, $firmwarerev, $softwarerev, $serialnum, $mfgname, $modelname, $alias, $assetid, $isfru)
{
    return array(
            'entPhysicalDescr' => $descr,
            'entPhysicalVendorType' => $vendortype,
            'entPhysicalContainedIn' => $containedin,
            'entPhysicalClass' => $class,
            'entPhysicalParentRelPos' => $parentrelpos,
            'entPhysicalName' => $name,
            'entPhysicalHardwareRev' => $hardwarerev,
            'entPhysicalFirmwareRev' => $firmwarerev,
            'entPhysicalSoftwareRev' => $softwarerev,
            'entPhysicalSerialNum' => $serialnum,
            'entPhysicalMfgName' => $mfgname,
            'entPhysicalModelName' => $modelname,
            'entPhysicalAlias' => $alias,
            'entPhysicalAssetID' => $assetid,
            'entPhysicalIsFRU' => $isfru
        );
}
