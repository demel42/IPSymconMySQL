<?php

if (!defined('IS_INVALIDCONFIG')) {
    define('IS_INVALIDCONFIG', IS_EBASE + 1);
}

class MySQL extends IPSModule
{
    public function Create()
    {
        parent::Create();

        $this->RegisterPropertyString('server', '');
        $this->RegisterPropertyInteger('port', '3306');
        $this->RegisterPropertyString('user', '');
        $this->RegisterPropertyString('password', '');
        $this->RegisterPropertyString('database', '');
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();

        $server = $this->ReadPropertyString('server');
        $port = $this->ReadPropertyInteger('port');
        $user = $this->ReadPropertyString('user');
        $password = $this->ReadPropertyString('password');
        $database = $this->ReadPropertyString('database');

        if ($server != '' && $port > 0) {
            $ok = true;
            if ($server == '') {
                echo 'no value for property "server"';
                $ok = false;
            }
            if ($user == '') {
                echo 'no value for property "user"';
                $ok = false;
            }
            if ($password == '') {
                echo 'no value for property "password"';
                $ok = false;
            }
            if ($database == '') {
                echo 'no value for property "database"';
                $ok = false;
            }
            $this->SetStatus($ok ? IS_ACTIVE : IS_INVALIDCONFIG);
        } else {
            $this->SetStatus(IS_INACTIVE);
        }
    }

    public function GetConfigurationForm()
    {
        $formElements = [];
        $formElements[] = ['type' => 'ValidationTextBox', 'name' => 'server', 'caption' => 'Server'];
        $formElements[] = ['type' => 'NumberSpinner', 'name' => 'port', 'caption' => 'Port'];
        $formElements[] = ['type' => 'ValidationTextBox', 'name' => 'user', 'caption' => 'User'];
        $formElements[] = ['type' => 'ValidationTextBox', 'name' => 'password', 'caption' => 'Password'];
        $formElements[] = ['type' => 'ValidationTextBox', 'name' => 'database', 'caption' => 'Database'];

        $formActions = [];
        $formActions[] = ['type' => 'Button', 'label' => 'Test connection', 'onClick' => 'MySQL_TestConnection($id);'];
		if (IPS_GetKernelVersion() < 5.2) {
			$formActions[] = ['type' => 'Label', 'label' => '____________________________________________________________________________________________________'];
			$formActions[] = ['type' => 'Button', 'label' => 'Module description', 'onClick' => 'echo \'https://github.com/demel42/IPSymconMySQL/blob/master/README.md\';'];
		}

        $formStatus = [];
        $formStatus[] = ['code' => IS_CREATING, 'icon' => 'inactive', 'caption' => 'Instance getting created'];
        $formStatus[] = ['code' => IS_ACTIVE, 'icon' => 'active', 'caption' => 'Instance is active'];
        $formStatus[] = ['code' => IS_DELETING, 'icon' => 'inactive', 'caption' => 'Instance is deleted'];
        $formStatus[] = ['code' => IS_INACTIVE, 'icon' => 'inactive', 'caption' => 'Instance is inactive'];
        $formStatus[] = ['code' => IS_NOTCREATED, 'icon' => 'inactive', 'caption' => 'Instance is not created'];

        $formStatus[] = ['code' => IS_INVALIDCONFIG, 'icon' => 'error', 'caption' => 'Instance is inactive (invalid configuration)'];

        return json_encode(['elements' => $formElements, 'actions' => $formActions, 'status' => $formStatus]);
    }

    public function TestConnection()
    {
        $rows = $this->ExecuteSimple('select now() as now');
        if ($rows && isset($rows[0]->now)) {
            $now = strtotime($rows[0]->now);
            $tstamp = date('d.m.Y H:i:s', $now);
            $n = $now - time();
            if (abs($n) > 1) {
                $msg = ', differ from localtime ' . $n . 'sec';
            } else {
                $msg = '';
            }
            echo 'current timestamp on database-server is ' . $tstamp . $msg;
            $this->SetStatus(IS_ACTIVE);
        } else {
            $this->SetStatus(IS_INVALIDCONFIG);
        }
    }

    public function Open()
    {
        $server = $this->ReadPropertyString('server');
        $port = $this->ReadPropertyInteger('port');
        $user = $this->ReadPropertyString('user');
        $password = $this->ReadPropertyString('password');
        $database = $this->ReadPropertyString('database');

        $this->SendDebug(__FUNCTION__, 'open database ' . $database . '@' . $server . ':' . $port . '(user=' . $user . ')', 0);

        $dbHandle = new mysqli($server, $user, $password, $database, $port);
        if ($dbHandle->connect_errno) {
            $this->SendDebug(__FUNCTION__, " => can't open database", 0);
            echo "can't open database " . $database . '@' . $server . ': ' . $dbHandle->connect_error . "\n";

            return false;
        }

        $this->SendDebug(__FUNCTION__, ' => dbHandle=' . print_r($dbHandle, true), 0);

        return $dbHandle;
    }

    public function Close(object $dbHandle)
    {
        if ($dbHandle && $dbHandle->close() == false) {
            echo "unable to close database\n";

            return false;
        }

        return true;
    }

    public function ExecuteSimple(string $statement)
    {
        $server = $this->ReadPropertyString('server');
        $database = $this->ReadPropertyString('database');

        $dbHandle = $this->Open();

        if ($dbHandle == false) {
            return $dbHandle;
        }

        $ret = $this->Query($dbHandle, $statement);

        return $ret;
    }

    public function Query(object $dbHandle, string $statement)
    {
        $server = $this->ReadPropertyString('server');
        $database = $this->ReadPropertyString('database');

        if ($dbHandle == false) {
            echo 'unable to execute statement "' . $statement . "\": invalid database-handle\n";

            return $dbHandle;
        }

        $this->SendDebug(__FUNCTION__, 'query "' . $statement . '" on ' . $database . '@' . $server, 0);
        $res = $dbHandle->query($statement);
        if ($res == false) {
            $this->SendDebug(__FUNCTION__, ' => unable to query', 0);
            echo 'unable to execute statement "' . $statement . '": ' . $dbHandle->error . "\n";

            return $res;
        }

        if (!isset($res->num_rows)) {
            return $res;
        }

        $this->SendDebug(__FUNCTION__, ' => got ' . $res->num_rows . ' rows', 0);

        $rows = [];
        while ($row = $res->fetch_object()) {
            $rows[] = $row;
        }
        $res->close();

        return $rows;
    }
}
