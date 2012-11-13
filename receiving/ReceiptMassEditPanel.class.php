<?php
// Company (Sender)[1], Company (Recipient)[1], Note, Date Due, Date Received
class ReceiptMassEditPanel extends QPanel {

    /**
     * @var  QCheckBox      $chkFromCompany
     * @var  QCheckBox      $chkToCompany
     * @var  QCheckBox      $chkNote
     * @var  QCheckBox      $chkDateDue
     * @var  QCheckBox      $chkDateReceived
     *
     * @var QTextBox        $txtNote
     * @var QDateTimePicker $calDateDue
     * @var QDateTimePicker $calDateReceived
     * @var QListBox        $lstFromCompany
     * @var QListBox        $lstFromContact
     * @var QListBox        $lstToCompany
     * @var QListBox        $lstToContact
     */

    // Specify the Location of the Template (feel free to modify) for this Panel
    protected $strTemplate = '../receiving/ReceiptMassEditPanel.tpl.php';

    // Inputs for can be Edited
    // public $txtLongDescription;
    protected $objCompanyArray;

    public $arrReceiptToEdit = array();
//	public $arrFields = array();
//
    public $chkFromCompany;
    public $chkToCompany;
    public $chkNote;
    public $chkDateDue;
    public $chkDateReceived;

    public $lstFromCompany;
    public $lstFromContact;
    public $lstFromAddress;
    public $lstToCompany;
    public $lstToContact;
    public $lstToAddress;
    public $calDateDue;
    public $calDateReceived;
    public $txtNote;

    public $btnApply;
    public $btnCancel;
    public $lblWarning;

    public function __construct($objParentObject, $strClosePanelMethod , $arrayReceiptId) {

        try {
            parent::__construct($objParentObject);
        } catch (QCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
        $this->objCompanyArray = Company::LoadAll(QQ::Clause(QQ::OrderBy(QQN::Company()->ShortDescription)));

        $this->arrReceiptToEdit = $arrayReceiptId;

        $this->chkDateDue_Create();
        $this->chkDateReceived_Create();
        $this->chkToCompany_Create();
        $this->chkFromCompany_Create();
        $this->chkNote_Create();

        $this->calDateDue_Create();
        $this->calDateReceived_Create();
        $this->lstToCompany_Create();
        $this->lstToContact_Create();
        $this->lstToAddress_Create();
        $this->lstFromCompany_Create();
        $this->lstFromContact_Create();
        $this->lstFromAddress_Create();
        $this->txtNote_Create();

        $this->btnApply_Create();
        $this->btnCancel_Create();

        // Disable inputs
        $this->calDateDue->Enabled = false;
        $this->calDateReceived->Enabled = false;
        $this->lstToCompany->Enabled = false;
        $this->lstToContact->Enabled = false;
        $this->lstToAddress->Enabled = false;
        $this->lstFromCompany->Enabled = false;
        $this->lstFromAddress->Enabled = false;
        $this->lstFromContact->Enabled = false;
        $this->txtNote->Enabled = false;
    }

    // Create the Note Input
    protected function txtNote_Create() {
        $this->txtNote = new QTextBox($this);
        $this->txtNote->Name = QApplication::Translate('Note');
        $this->txtNote->TextMode = QTextMode::MultiLine;
        $this->txtNote->Height=80;
        $this->txtNote->strControlId = 'note';
    }

    public function chkNote_Create(){
        $this->chkNote = new QCheckBox($this);
        $this->chkNote->Name = 'note';
        $this->chkNote->strControlId = 'chk_note';
        $this->chkNote->Checked = false;
        $this->chkNote->AddAction(new QClickEvent(), new QJavaScriptAction("enableInput(this)"));
    }

    // Create and Setup lstFromCompany
    protected function lstFromCompany_Create() {
        $this->lstFromCompany = new QListBox($this);
        $this->lstFromCompany->Name = QApplication::Translate('From Company');
        $this->lstFromCompany->AddItem('- Select One -', null);
        if ($this->objCompanyArray) foreach ($this->objCompanyArray as $objToCompany) {
            $objListItem = new QListItem($objToCompany->__toString(), $objToCompany->CompanyId);
            $this->lstFromCompany->AddItem($objListItem);
        }
        $this->lstFromCompany->strControlId = 'from_company';
    }

    public function chkFromCompany_Create(){
        $this->chkFromCompany = new QCheckBox($this);
        $this->chkFromCompany->Name = 'from_company';
        $this->chkFromCompany->strControlId = 'chk_from_company';
        $this->chkFromCompany->Checked = false;
        $this->chkFromCompany->AddAction(new QClickEvent(),
            new QJavaScriptAction("enableInput(this,['from_contact','from_address'])"));

    }

    // Create and Setup lstToCompany
    protected function lstToCompany_Create() {
        $this->lstToCompany = new QListBox($this);
        $this->lstToCompany->Name = QApplication::Translate('To Company');
        $this->lstToCompany->AddItem('- Select One -', null);
        if ($this->objCompanyArray) foreach ($this->objCompanyArray as $objToCompany) {
            $objListItem = new QListItem($objToCompany->__toString(), $objToCompany->CompanyId);
            $this->lstToCompany->AddItem($objListItem);
        }
        $this->lstToCompany->strControlId = 'to_company';

    }

    public function chkToCompany_Create(){
        $this->chkToCompany = new QCheckBox($this);
        $this->chkToCompany->Name = 'to_company';
        $this->chkToCompany->strControlId = 'chk_to_company';
        $this->chkToCompany->Checked = false;
        $this->chkToCompany->AddAction(new QClickEvent(),
            new QJavaScriptAction("enableInput(this,['to_contact','to_address'])"));
    }

    // To Contact, To Address inputs
    public function lstToContact_Create(){
        $this->lstToContact = new QListBox($this);
        $this->lstToContact->Name = 'To Contact';
        $this->lstToContact->strControlId = 'to_contact';
    }

    public function lstToAddress_Create(){
        $this->lstToAddress = new QListBox($this);
        $this->lstToAddress->Name = 'To Address';
        $this->lstToAddress->strControlId = 'to_address';
    }
    // From Contact, To Address inputs
    public function lstFromContact_Create(){
        $this->lstFromContact = new QListBox($this);
        $this->lstFromContact->Name = 'From Contact';
        $this->lstFromContact->strControlId = 'from_contact';
    }

    public function lstFromAddress_Create(){
        $this->lstFromAddress = new QListBox($this);
        $this->lstFromAddress->Name = 'From Address';
        $this->lstFromAddress->strControlId = 'from_address';
    }

    // Date Due Inputs
    public function calDateDue_Create(){
        $this->calDateDue = new QDateTimePickerExt($this);
        $this->calDateDue->Name = QApplication::Translate('Date Due');
        $this->calDateDue->DateTimePickerType = QDateTimePickerType::Date;
        $this->calDateDue->DateTime = new QDateTime(QDateTime::Now);
        $dttNow = new QDateTime(QDateTime::Now);
        $this->calDateDue->MaximumYear = $dttNow->Year + 30;
        $this->calDateDue->strControlId = 'date_due';
    }
    //
    public function chkDateDue_Create(){
        $this->chkDateDue = new QCheckBox($this);
        $this->chkDateDue->Name = 'date_due';
        $this->chkDateDue->strControlId = 'chk_date_due';
        $this->chkDateDue->Checked = false;
        $this->chkDateDue->AddAction(new QClickEvent(), new QJavaScriptAction('enableCalInput(this)'));
    }
    // Date Received Inputs
    public function calDateReceived_Create(){
        $this->calDateReceived = new QDateTimePickerExt($this);
        $this->calDateReceived->Name = QApplication::Translate('Date Received');
        $this->calDateReceived->DateTimePickerType = QDateTimePickerType::Date;
        $this->calDateReceived->DateTime = new QDateTime(QDateTime::Now);
        $dttNow = new QDateTime(QDateTime::Now);
        $this->calDateReceived->MaximumYear = $dttNow->Year + 30;
        $this->calDateReceived->strControlId = 'date_received';
    }
    public function chkDateReceived_Create(){
        $this->chkDateReceived = new QCheckBox($this);
        $this->chkDateReceived->Name = 'date_received';
        $this->chkDateReceived->strControlId = 'chk_date_received';
        $this->chkDateReceived->Checked = false;
        $this->chkDateReceived->AddAction(new QClickEvent(), new QJavaScriptAction('enableCalInput(this)'));
    }

    public function btnApply_Create(){

        $this->btnApply = new QButton($this);
        $this->btnApply->Name = 'Apply';
        $this->btnApply->Text = 'Apply';
        $this->btnApply->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnApply_Click'));
        $this->btnApply->AddAction(new QEnterKeyEvent(), new QAjaxControlAction($this, 'btnApply_Click'));
        $this->btnApply->AddAction(new QEnterKeyEvent(), new QTerminateAction());

    }

    public function btnCancel_Create(){

        $this->btnCancel = new QButton($this);
        $this->btnCancel->Name = 'Cancel';
        $this->btnCancel->Text = 'Cancel';
        $this->btnCancel->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'btnCancel_Click'));
        $this->btnCancel->AddAction(new QEnterKeyEvent(), new QAjaxControlAction($this, 'btnCancel_Click'));
        $this->btnCancel->AddAction(new QEnterKeyEvent(), new QTerminateAction());

    }

    public function lblWarning_Create(){
        $this->lblWarning = new QLabel($this);
        $this->lblWarning->Class = 'warning';
    }

    public function btnApply_Click($strFormId, $strControlId, $strParameter){
        $this->ParentControl->HideDialogBox();
    }


    // Cancel Button Click Action
    public function btnCancel_Click($strFormId, $strControlId, $strParameter) {
        //$this->ParentControl->RemoveChildControls(true);
        //$this->CloseSelf(true);
        $this->ParentControl->HideDialogBox();
    }

    public function lstToCompany_Select(){

    }

    public function lstFromCompany_Select(){

    }

}
?>