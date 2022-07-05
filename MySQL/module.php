<?php

declare(strict_types=1);

require_once __DIR__ . '/../libs/common.php';
require_once __DIR__ . '/../libs/local.php';

class MySQL extends IPSModule
{
    use MySQL\StubsCommonLib;
    use MySQLLocalLib;

    private $ModuleDir;

    public function __construct(string $InstanceID)
    {
        parent::__construct($InstanceID);

        $this->ModuleDir = __DIR__;
    }

    public function Create()
    {
        parent::Create();

        $this->RegisterPropertyString('server', '');
        $this->RegisterPropertyInteger('port', '3306');
        $this->RegisterPropertyString('user', '');
        $this->RegisterPropertyString('password', '');
        $this->RegisterPropertyString('database', '');

        $this->RegisterAttributeString('UpdateInfo', '');

        $this->InstallVarProfiles(false);
    }

    private function CheckModuleConfiguration()
    {
        $r = [];

        $server = $this->ReadPropertyString('server');
        if ($server == '') {
            $this->SendDebug(__FUNCTION__, '"server" is missing', 0);
            $r[] = $this->Translate('Server must be specified');
        }

        $user = $this->ReadPropertyString('user');
        if ($user == '') {
            $this->SendDebug(__FUNCTION__, '"user" is missing', 0);
            $r[] = $this->Translate('User must be specified');
        }

        $password = $this->ReadPropertyString('password');
        if ($password == '') {
            $this->SendDebug(__FUNCTION__, '"password" is missing', 0);
            $r[] = $this->Translate('Password must be specified');
        }

        $database = $this->ReadPropertyString('database');
        if ($database == '') {
            $this->SendDebug(__FUNCTION__, '"database" is missing', 0);
            $r[] = $this->Translate('Database must be specified');
        }

        return $r;
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();

        $this->MaintainReferences();

        if ($this->CheckPrerequisites() != false) {
            $this->MaintainStatus(self::$IS_INVALIDPREREQUISITES);
            return;
        }

        if ($this->CheckUpdate() != false) {
            $this->MaintainStatus(self::$IS_UPDATEUNCOMPLETED);
            return;
        }

        if ($this->CheckConfiguration() != false) {
            $this->MaintainStatus(self::$IS_INVALIDCONFIG);
            return;
        }

        $this->MaintainStatus(IS_ACTIVE);
    }

    private function GetFormElements()
    {
        $formElements = $this->GetCommonFormElements('MySQL');

        if ($this->GetStatus() == self::$IS_UPDATEUNCOMPLETED) {
            return $formElements;
        }

        $formElements[] = [
            'name'    => 'server',
            'type'    => 'ValidationTextBox',
            'caption' => 'Server',
        ];
        $formElements[] = [
            'name'    => 'port',
            'type'    => 'NumberSpinner',
            'minimum' => 0,
            'caption' => 'Port',
        ];
        $formElements[] = [
            'name'    => 'user',
            'type'    => 'ValidationTextBox',
            'caption' => 'User',
        ];
        $formElements[] = [
            'name'    => 'password',
            'type'    => 'PasswordTextBox',
            'caption' => 'Password'
        ];
        $formElements[] = [
            'name'    => 'database',
            'type'    => 'ValidationTextBox',
            'caption' => 'Database',
        ];

        return $formElements;
    }

    private function GetFormActions()
    {
        $formActions = [];

        if ($this->GetStatus() == self::$IS_UPDATEUNCOMPLETED) {
            $formActions[] = $this->GetCompleteUpdateFormAction();

            $formActions[] = $this->GetInformationFormAction();
            $formActions[] = $this->GetReferencesFormAction();

            return $formActions;
        }

        $formActions[] = [
            'type'    => 'Button',
            'caption' => 'Test connection',
            'onClick' => 'IPS_RequestAction(' . $this->InstanceID . ', "TestConnection", "");',
        ];

        $formActions[] = $this->GetInformationFormAction();
        $formActions[] = $this->GetReferencesFormAction();

        return $formActions;
    }

    public function RequestAction($ident, $value)
    {
        if ($this->CommonRequestAction($ident, $value)) {
            return;
        }
        switch ($ident) {
            case 'TestConnection':
                $this->TestConnection();
                break;
            default:
                $this->SendDebug(__FUNCTION__, 'invalid ident ' . $ident, 0);
                break;
        }
    }

    private function TestConnection()
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
            $msg = 'current timestamp on database-server is ' . $tstamp . $msg;
            $this->PopupMessage($msg);
            $this->MaintainStatus(IS_ACTIVE);
        } else {
            $this->MaintainStatus(self::$IS_INVALIDCONFIG);
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
