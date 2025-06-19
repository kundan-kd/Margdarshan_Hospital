<?php

use App\Http\Controllers\backend\admin\appointment\AppointmentController;
use App\Http\Controllers\backend\admin\appointment\PatientController;
use App\Http\Controllers\backend\admin\CommonController;
use App\Http\Controllers\backend\admin\emergency\EmergencyController;
use App\Http\Controllers\backend\admin\home\ProfileController;
use App\Http\Controllers\backend\admin\ipdin\IpdinController;
use App\Http\Controllers\backend\admin\master\BedController;
use App\Http\Controllers\backend\admin\master\BedgroupController;
use App\Http\Controllers\backend\admin\master\BedtypeController;
use App\Http\Controllers\backend\admin\master\BloodtypeController;
use App\Http\Controllers\backend\admin\master\CompanyController;
use App\Http\Controllers\backend\admin\master\DepartmentController;
use App\Http\Controllers\backend\admin\master\MedicinecategoryController;
use App\Http\Controllers\backend\admin\master\MedicinegroupController;
use App\Http\Controllers\backend\admin\master\PaymentmodeController;
use App\Http\Controllers\backend\admin\master\UnitController;
use App\Http\Controllers\backend\admin\master\UserController;
use App\Http\Controllers\backend\auth\AuthenticationController;
use App\Http\Controllers\backend\admin\master\UsertypeController;
use App\Http\Controllers\backend\admin\master\VendorController;
use App\Http\Controllers\backend\admin\opdout\OpdoutController;
use App\Http\Controllers\backend\admin\pharmacy\BillingController;
use App\Http\Controllers\backend\admin\pharmacy\MedicineController;
use App\Http\Controllers\backend\admin\pharmacy\PurchaseController;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Models\Company;
use Illuminate\Support\Facades\Route;

//  Route::get('/appointment', function () {return view('backend.admin.modules.appointment.appointment');});



Route::get('/', function () {return view('backend.auth.login');});
Route::get('/login',[AuthenticationController::class,'index'])->name('auth.login-page');
Route::post('/login-process',[AuthenticationController::class,'login'])->name('auth.login');
Route::post('/send-otp',[AuthenticationController::class,'sendotp'])->name('auth.send-pass-otp');
Route::post('/verify-otp',[AuthenticationController::class,'verifyotp'])->name('auth.verify-pass-otp');
Route::post('/update-password',[AuthenticationController::class,'updatepass'])->name('auth.new-pass-update');
Route::get('/logout',[AuthenticationController::class,'logout'])->name('auth.logout');

Route::middleware(['prevent-back'])->group(function () {
Route::get('/my-dashboard',[AuthenticationController::class,'dashboard'])->name('auth.dashboard');
Route::get('/profile',[ProfileController::class,'index'])->name('profile.index');

Route::get('/usertype',[UsertypeController::class,'index'])->name('usertype.index');
Route::post('/usertype-details',[UsertypeController::class,'viewUserTypes'])->name('usertype-view.viewUserTypes');
Route::post('/usertype-add',[UsertypeController::class,'addUserType'])->name('usertype-add.addUserType');
Route::post('/usertype-getdetails',[UsertypeController::class,'getUserTypeData'])->name('usertype-getdetails.getUserTypeData');
Route::post('/usertype-update',[UsertypeController::class,'updateUserTypeData'])->name('usertype-update.updateUserTypeData');
Route::post('/usertype-status-update',[UsertypeController::class,'statusUpdate'])->name('usertype-status-update.statusUpdate');
Route::post('/usertype-delete',[UsertypeController::class,'deleteUserTypeData'])->name('usertype-delete.deleteUserTypeData');

Route::get('/department',[DepartmentController::class,'index'])->name('department.index');
Route::post('/department-details',[DepartmentController::class,'viewDepartments'])->name('department-view.viewDepartments');
Route::post('/department-add',[DepartmentController::class,'addDepartment'])->name('department-add.addDepartment');
Route::post('/department-getdetails',[DepartmentController::class,'getDepartmentData'])->name('department-getdetails.getDepartmentData');
Route::post('/department-update',[DepartmentController::class,'updateDepartmentData'])->name('department-update.updateDepartmentData');
Route::post('/department-status-update',[DepartmentController::class,'statusUpdate'])->name('department-status-update.statusUpdate');
Route::post('/department-delete',[DepartmentController::class,'deleteDepertmentData'])->name('department-delete.deleteDepertmentData');

Route::get('/paymentmode',[PaymentmodeController::class,'index'])->name('paymentmode.index');
Route::post('/paymentmode-details',[PaymentmodeController::class,'viewPaymentModes'])->name('paymentmode-view.viewPaymentModes');
Route::post('/paymentmode-add',[PaymentmodeController::class,'addPaymentMode'])->name('paymentmode-add.addPaymentMode');
Route::post('/paymentmode-getdetails',[PaymentmodeController::class,'getPaymentModeData'])->name('paymentmode-getdetails.getPaymentModeData');
Route::post('/paymentmode-update',[PaymentmodeController::class,'updatePaymentModeData'])->name('paymentmode-update.updatePaymentModeData');
Route::post('/paymentmode-status-update',[PaymentmodeController::class,'statusUpdate'])->name('paymentmode-status-update.statusUpdate');
Route::post('/paymentmode-delete',[PaymentmodeController::class,'deletePaymentModeData'])->name('paymentmode-delete.deletePaymentModeData');

Route::get('/appointment',[AppointmentController::class,'index'])->name('appointment.index');
Route::post('/appointment-details',[AppointmentController::class,'viewAppointments'])->name('appointment.viewAppointments');
Route::post('/appointment-addpatient',[AppointmentController::class,'addNewPatient'])->name('appointment-patient.addNewPatient');
Route::post('/appointment-patient-search',[AppointmentController::class,'searchPatient'])->name('appointment-patient.searchPatient');
Route::post('/appointment-patient-getdetails',[AppointmentController::class,'getPatient'])->name('appointment-patient.getPatient');
Route::post('/appointment-doctor-details',[AppointmentController::class,'getDoctorData'])->name('appointment-patient.getDoctorData');
Route::post('/appointment-booking',[AppointmentController::class,'appointmentBook'])->name('appointment-booking.appointmentBook');
Route::post('/appointment-booking-getdetails',[AppointmentController::class,'getAppointmentData'])->name('appointment-booking.getAppointmentData');
Route::post('/appointment-booking-update',[AppointmentController::class,'updateAppointmentData'])->name('appointment-booking.updateAppointmentData');
Route::post('/appointment-booking-delete',[AppointmentController::class,'deleteAppointmentData'])->name('appointment-booking.deleteAppointmentData');

Route::get('/patient',[PatientController::class,'index'])->name('patient.index');
Route::post('/patient-details',[PatientController::class,'viewPatients'])->name('patient.viewPatients');
Route::post('/patient-add-new',[PatientController::class,'patientAddNewPatient'])->name('patient.patientAddNewPatient');
Route::post('/patient-data',[PatientController::class,'newPatientData'])->name('patient.newPatientData');
Route::post('/patient-update',[PatientController::class,'patientAddNewPatientDataUpdate'])->name('patient.patientAddNewPatientDataUpdate');
Route::post('/patient-delete',[PatientController::class,'deletePatientData'])->name('patient.deletePatientData');

Route::get('/medicine',[MedicineController::class,'index'])->name('medicine.index');
Route::get('/medicine-details',[MedicineController::class,'medicineView'])->name('medicine.medicineView');
Route::post('/medicine-add',[MedicineController::class,'medicineAdd'])->name('medicine.medicineAdd');
Route::post('/medicine-data',[MedicineController::class,'getMedicineData'])->name('medicine.getMedicineData');
Route::post('/medicine-update',[MedicineController::class,'updateMedicineData'])->name('medicine.updateMedicineData');
Route::post('/medicine-delete',[MedicineController::class,'deleteMedicineData'])->name('medicine.deleteMedicineData');
Route::get('/medicine-view/{id}',[MedicineController::class,'medicineViewIndex']);

Route::get('/purchase',[PurchaseController::class,'index'])->name('purchase.index');
Route::get('/purchase-add',[PurchaseController::class,'purchaseAdd'])->name('purchase.purchaseAdd');
Route::get('/purchase-details',[PurchaseController::class,'purchaseView'])->name('purchase.purchaseView');
Route::post('/purchase-add',[PurchaseController::class,'purchaseAddDatas'])->name('purchase.purchaseAddDatas');
Route::get('/purchase-edit/{id}',[PurchaseController::class,'purchaseEditPage']);
Route::post('/purchase-update',[PurchaseController::class,'purchaseUpdateDatas'])->name('purchase.purchaseUpdateDatas');
Route::post('/purchase-details-delete',[PurchaseController::class,'deletePurchasedetails'])->name('purchase.deletePurchasedetails');
Route::get('/purchase-edit-details',[PurchaseController::class,'getPurchaseNamesSelectEdit'])->name('purchase.getPurchaseNamesSelectEdit');
Route::get('/purchase-category-data',[PurchaseController::class,'getCategoryDatas'])->name('purchase.getCategoryDatas');
Route::get('/purchase-view/{id}',[PurchaseController::class,'pruchaseViewIndex']);
Route::post('/purchase-data',[PurchaseController::class,'getPurchaseData'])->name('purchase.getPurchaseData');

Route::get('/medicine-category',[MedicinecategoryController::class,'index'])->name('medicine-category.index');
Route::post('/medicine-category-details',[MedicinecategoryController::class,'viewMedicineCategory'])->name('medicine-category.viewMedicineCategory');
Route::post('/medicine-category-add',[MedicinecategoryController::class,'addMedicineCategory'])->name('medicine-category.addMedicineCategory');
Route::post('/medicine-category-getdetails',[MedicinecategoryController::class,'getMedicineCategoryData'])->name('medicine-category.getMedicineCategoryData');
Route::post('/medicine-category-update',[MedicinecategoryController::class,'updateMedicineCategoryData'])->name('medicine-category.updateMedicineCategoryData');
Route::post('/medicine-category-status-update',[MedicinecategoryController::class,'statusUpdate'])->name('medicine-category.statusUpdate');
Route::post('/medicine-category-delete',[MedicinecategoryController::class,'deleteMedicineCategory'])->name('medicine-category.deleteMedicineCategory');

Route::get('/company',[CompanyController::class,'index'])->name('company.index');
Route::post('/company-details',[CompanyController::class,'viewCompany'])->name('company.viewCompany');
Route::post('/company-add',[CompanyController::class,'addCompany'])->name('company.addCompany');
Route::post('/company-getdetails',[CompanyController::class,'getCompanyData'])->name('company.getCompanyData');
Route::post('/company-update',[CompanyController::class,'updateCompanyData'])->name('company.updateCompanyData');
Route::post('/company-status-update',[CompanyController::class,'statusUpdate'])->name('company.statusUpdate');
Route::post('/company-delete',[CompanyController::class,'deleteCompany'])->name('company.deleteCompany');

Route::get('/medicine-group',[MedicinegroupController::class,'index'])->name('medicine-group.index');
Route::post('/medicine-group-details',[MedicinegroupController::class,'viewMedicineGroup'])->name('medicine-group.viewMedicineGroup');
Route::post('/medicine-group-add',[MedicinegroupController::class,'addMedicineGroup'])->name('medicine-group.addMedicineGroup');
Route::post('/medicine-group-getdetails',[MedicinegroupController::class,'getMedicineGroupData'])->name('medicine-group.getMedicineGroupData');
Route::post('/medicine-group-update',[MedicinegroupController::class,'updateMedicineGroupData'])->name('medicine-group.updateMedicineGroupData');
Route::post('/medicine-group-status-update',[MedicinegroupController::class,'statusUpdate'])->name('medicine-group.statusUpdate');
Route::post('/medicine-group-delete',[MedicinegroupController::class,'deleteMedicineGroup'])->name('medicine-group.deleteMedicineGroup');

Route::get('/unit',[UnitController::class,'index'])->name('unit.index');
Route::post('/unit-details',[UnitController::class,'viewUnit'])->name('unit.viewUnit');
Route::post('/unit-add',[UnitController::class,'addUnit'])->name('unit.addUnit');
Route::post('/unit-getdetails',[UnitController::class,'getUnitData'])->name('unit.getUnitData');
Route::post('/unit-update',[UnitController::class,'updateUnitData'])->name('unit.updateUnitData');
Route::post('/unit-status-update',[UnitController::class,'statusUpdate'])->name('unit.statusUpdate');
Route::post('/unit-delete',[UnitController::class,'deleteUnit'])->name('unit.deleteUnit');

Route::get('/blood-type',[BloodtypeController::class,'index'])->name('blood-type.index');
Route::post('/blood-type-details',[BloodTypeController::class,'viewBloodType'])->name('blood-type.viewBloodType');
Route::post('/blood-type-add',[BloodTypeController::class,'addBloodType'])->name('blood-type.addBloodType');
Route::post('/blood-type-getdetails',[BloodTypeController::class,'getBloodTypeData'])->name('blood-type.getBloodTypeData');
Route::post('/blood-type-update',[BloodTypeController::class,'updateBloodTypeData'])->name('blood-type.updateBloodTypeData');
Route::post('/blood-type-status-update',[BloodTypeController::class,'statusUpdate'])->name('blood-type.statusUpdate');
Route::post('/blood-type-delete',[BloodTypeController::class,'deleteBloodType'])->name('blood-type.deleteBloodType');
   
Route::get('/vendor',[VendorController::class,'index'])->name('vendor.index');
Route::post('/vendor-details',[VendorController::class,'viewVendor'])->name('vendor.viewVendor');
Route::post('/vendor-add',[VendorController::class,'addVendor'])->name('vendor.addVendor');
Route::post('/vendor-getdetails',[VendorController::class,'getVendorData'])->name('vendor.getVendorData');
Route::post('/vendor-update',[VendorController::class,'updateVendorData'])->name('vendor.updateVendorData');
Route::post('/vendor-status-update',[VendorController::class,'statusUpdate'])->name('vendor.statusUpdate');
Route::post('/vendor-delete',[VendorController::class,'deleteVendor'])->name('vendor.deleteVendor');

Route::get('/bedtype',[BedtypeController::class,'index'])->name('bedtype.index');
Route::post('/bedtype-details',[BedtypeController::class,'viewBedTypes'])->name('bedtype.viewBedTypes');
Route::post('/bedtype-add',[BedtypeController::class,'addBedType'])->name('bedtype.addBedType');
Route::post('/bedtype-getdetails',[BedtypeController::class,'getBedTypeData'])->name('bedtype.getBedTypeData');
Route::post('/bedtype-update',[BedtypeController::class,'updateBedTypeData'])->name('bedtype.updateBedTypeData');
Route::post('/bedtype-status-update',[BedtypeController::class,'statusUpdate'])->name('bedtype.statusUpdate');
Route::post('/bedtype-delete',[BedtypeController::class,'deleteBedTypeData'])->name('bedtype.deleteBedTypeData');

Route::get('/bedgroup',[BedgroupController::class,'index'])->name('bedgroup.index');
Route::post('/bedgroup-details',[BedgroupController::class,'viewBedGroups'])->name('bedgroup.viewBedGroups');
Route::post('/bedgroup-add',[BedgroupController::class,'addBedGroup'])->name('bedgroup.addBedGroup');
Route::post('/bedgroup-getdetails',[BedgroupController::class,'getBedGroupData'])->name('bedgroup.getBedGroupData');
Route::post('/bedgroup-update',[BedgroupController::class,'updateBedGroupData'])->name('bedgroup.updateBedGroupData');
Route::post('/bedgroup-status-update',[BedgroupController::class,'statusUpdate'])->name('bedgroup.statusUpdate');
Route::post('/bedgroup-delete',[BedgroupController::class,'deleteBedGroupData'])->name('bedgroup.deleteBedGroupData');

Route::get('/bed',[BedController::class,'index'])->name('bed.index');
Route::post('/bed-details',[BedController::class,'viewBeds'])->name('bed.viewBeds');
Route::post('/bed-add',[BedController::class,'addBed'])->name('bed.addBed');
Route::post('/bed-getdetails',[BedController::class,'getBedData'])->name('bed.getBedData');
Route::post('/bed-update',[BedController::class,'updateBedData'])->name('bed.updateBedData');
Route::post('/bed-status-update',[BedController::class,'statusUpdate'])->name('bed.statusUpdate');
Route::post('/bed-delete',[BedController::class,'deleteBedData'])->name('bed.deleteBedData');

Route::get('/user',[UserController::class,'index'])->name('user.index');

Route::get('/billing',[BillingController::class,'index'])->name('billing.index');
Route::get('/billing-view',[BillingController::class,'billingView'])->name('billing.billingView');
Route::get('/billing-add',[BillingController::class,'billingAdd'])->name('billing.billingAdd');
Route::get('/billing-medicine-name',[BillingController::class,'getMedicineNames'])->name('billing.getMedicineNames');
Route::get('/billing-add-batch',[BillingController::class,'getBatchNumbers'])->name('billing-add.getBatchNumbers');
Route::get('/billing-add-batch-expity',[BillingController::class,'getBatchExpiryDate'])->name('billing-add.getBatchExpiryDate');
Route::post('/billing-add-data',[BillingController::class,'billingAddDatas'])->name('billing-add.billingAddDatas');
Route::get('/billing-edit-page/{id}',[BillingController::class,'billingEditPage']);

Route::post('/billing-edit-data-load',[BillingController::class,'billingEditAutoLoadData'])->name('billing-edit.billingEditAutoLoadData');

Route::get('/billing-edit-name-details',[BillingController::class,'getBillingNamesSelectEdit'])->name('billing-edit.getBillingNamesSelectEdit');
Route::post('/billing-update',[BillingController::class,'billingUpdateDatas'])->name('billing-Edit.billingEditDatas');

Route::get('/opd-out',[OpdoutController::class,'index'])->name('opd-out.index');
Route::post('/opd-out-view',[OpdoutController::class,'viewOpdOut'])->name('opd-out.viewOpdOut');
Route::get('/opd-out-details/{id}',[OpdoutController::class,'opdOutDetails']);
Route::post('/opd-out-movetoipd',[OpdoutController::class,'moveToIpdStatus'])->name('opd-out.moveToIpdStatus');
Route::post('/opd-out-visit-add',[OpdoutController::class,'opdOutVisitSubmit'])->name('opd-out-visit.opdOutVisitSubmit');
Route::post('/opd-out-visit-view',[OpdoutController::class,'viewOptOutVisit'])->name('opd-out-visit.viewOptOutVisit');
Route::post('/opd-out-visit-data',[OpdoutController::class,'getOpdOutVisitData'])->name('opd-out-visit.getOpdOutVisitData');
Route::post('/opd-out-visit-update',[OpdoutController::class,'opdOutVisitDataUpdate'])->name('opd-out-visit.opdOutVisitDataUpdate');
Route::post('/opd-out-visit-delete',[OpdoutController::class,'opdOutVisitDataDelete'])->name('opd-out-visit.opdOutVisitDataDelete');
Route::post('/opd-out-medicine-dose-add',[OpdoutController::class,'opdOutMedDataAdd'])->name('opd-out-med.opdOutMedDataAdd');
Route::post('/opd-out-medicine-dose-view',[OpdoutController::class,'viewOptOutMedDose'])->name('opd-out-med.viewOptOutMedDose');
Route::post('/opd-out-medicine-dose-data',[OpdoutController::class,'getOpdOutMedDoseDetails'])->name('opd-out-med.getOpdOutMedDoseDetails');
Route::post('/opd-out-medicine-dose-update',[OpdoutController::class,'opdOutMedDataUpdate'])->name('opd-out-med.opdOutMedDataUpdate');
Route::post('/opd-out-medicine-dose-delete',[OpdoutController::class,'opdOutMedDoseDataDelete'])->name('opd-out-med.opdOutMedDoseDataDelete');
Route::post('/opd-out-lab-test-add',[OpdoutController::class,'opdOutLabSubmit'])->name('opd-out-lab.opdOutLabSubmit');
Route::post('/opd-out-lab-test-details',[OpdoutController::class,'viewOpdOutLabDetails'])->name('opd-out-lab.viewOpdOutLabDetails');
Route::post('/opd-out-lab-test-data',[OpdoutController::class,'getOpdOutLabData'])->name('opd-out-lab.getOpdOutLabData');
Route::post('/opd-out-lab-test-edit',[OpdoutController::class,'getOpdOutLabDetails'])->name('opd-out-lab.getOpdOutLabDetails');
Route::post('/opd-out-lab-test-update',[OpdoutController::class,'opdOutLabUpdateData'])->name('opd-out-lab.opdOutLabUpdateData');
Route::post('/opd-out-lab-test-delete',[OpdoutController::class,'opdOutLabDataDelete'])->name('opd-out-lab.opdOutLabDataDelete');

Route::post('/opd-out-charge-add',[OpdoutController::class,'opdOutChargeSubmit'])->name('opd-out-charge.opdOutChargeSubmit');
Route::post('/opd-out-charge-view',[OpdoutController::class,'viewOpdOutCharge'])->name('opd-out-charge.viewOpdOutCharge');
Route::post('/opd-out-charge-edit',[OpdoutController::class,'getOpdOutChargeData'])->name('opd-out-charge.getOpdOutChargeData');
Route::post('/opd-out-charge-update',[OpdoutController::class,'opdOutChargeDataUpdate'])->name('opd-out-charge.opdOutChargeDataUpdate');
Route::post('/opd-out-charge-delete',[OpdoutController::class,'opdOutChargeDataDelete'])->name('opd-out-charge.opdOutChargeDataDelete');

Route::post('/opd-out-vital-add',[OpdoutController::class,'opdOutVItalSubmit'])->name('opd-out-vital.opdOutVItalSubmit');
Route::post('/opd-out-vital-view',[OpdoutController::class,'viewOpdOutVital'])->name('opd-out-vital.viewOpdOutVital');
Route::post('/opd-out-vital-data',[OpdoutController::class,'getOpdOutVitalData'])->name('opd-out-vital.getOpdOutVitalData');
Route::post('/opd-out-vital-update',[OpdoutController::class,'opdOutVItalDataUpdate'])->name('opd-out-vital.opdOutVItalDataUpdate');
Route::post('/opd-out-vital-delete',[OpdoutController::class,'opdOutVitalDataDelete'])->name('opd-out-vital.opdOutVitalDataDelete');


Route::get('/ipd-in',[IpdinController::class,'index'])->name('ipd-in.index');
Route::post('/ipd-in-patient-add',[IpdinController::class,'ipdInPatientAdd'])->name('ipd-in.ipdInPatientAdd');
Route::get('/ipd-in-details/{id}',[IpdinController::class,'ipdInDetails']);
Route::post('/ipd-in-patient-movetoemergency',[IpdinController::class,'moveToEmergencyStatus'])->name('ipd.moveToEmergencyStatus');
Route::post('/ipd-in-patient-discharge',[IpdinController::class,'patientDischargeStatus'])->name('ipd.patientDischargeStatus');
Route::post('/ipd-in-patient-add',[IpdinController::class,'addNewPatientIpd'])->name('ipd-addPatient');
Route::post('/ipd-in-patient-view',[IpdinController::class,'viewPatients'])->name('ipd-viewPatients');
Route::post('/ipd-patient-data',[IpdinController::class,'getIpdPatientData'])->name('ipd-getIpdPatientData');
Route::post('/ipd-patient-update',[IpdinController::class,'ipdPatientDataUpdate'])->name('ipd-ipdPatientDataUpdate');
Route::post('/ipd-patient-delete',[IpdinController::class,'ipdPatientDataDelete'])->name('ipd-ipdPatientDataDelete');
Route::post('/ipd-in-visit-add',[IpdinController::class,'ipdVisitSubmit'])->name('ipd-visit.ipdVisitSubmit');
Route::post('/ipd-in-visit-view',[IpdinController::class,'viewIpdVisit'])->name('ipd-visit.viewIpdVisit');
Route::post('/ipd-in-visit-data',[IpdinController::class,'getIpdVisitData'])->name('ipd-visit.getIpdVisitData');
Route::post('/ipd-in-visit-update',[IpdinController::class,'ipdVisitDataUpdate'])->name('ipd-visit.ipdVisitDataUpdate');
Route::post('/ipd-in-visit-delete',[IpdinController::class,'ipdVisitDataDelete'])->name('ipd-visit.ipdVisitDataDelete');
Route::post('/ipd-in-medicine-dose-add',[IpdinController::class,'ipdMedDataAdd'])->name('ipd-med.ipdMedDataAdd');
Route::post('/ipd-in-medicine-dose-view',[IpdinController::class,'viewIpdMedDose'])->name('ipd-med.viewIpdMedDose');
Route::post('/ipd-in-medicine-dose-data',[IpdinController::class,'getIpdMedDoseDetails'])->name('ipd-med.getIpdMedDoseDetails');
Route::post('/ipd-in-medicine-dose-update',[IpdinController::class,'ipdMedDataUpdate'])->name('ipd-med.ipdMedDataUpdate');
Route::post('/ipd-in-medicine-dose-delete',[IpdinController::class,'ipdMedDoseDataDelete'])->name('ipd-med.ipdMedDoseDataDelete');

Route::post('/ipd-lab-test-add',[IpdinController::class,'ipdLabSubmit'])->name('ipd-lab.ipdLabSubmit');
Route::post('/ipd-lab-test-details',[IpdinController::class,'viewIpdLabDetails'])->name('ipd-lab.viewIpdLabDetails');
Route::post('/ipd-lab-test-data',[IpdinController::class,'getIpdLabData'])->name('ipd-lab.getIpdLabData');
Route::post('/ipd-lab-test-edit',[IpdinController::class,'getIpdLabDetails'])->name('ipd-lab.getIpdLabDetails');
Route::post('/ipd-lab-test-update',[IpdinController::class,'ipdLabUpdateData'])->name('ipd-lab.ipdLabUpdateData');
Route::post('/ipd-lab-test-delete',[IpdinController::class,'ipdLabDataDelete'])->name('ipd-lab.ipdLabDataDelete');

Route::post('/ipd-charge-add',[IpdinController::class,'ipdChargeSubmit'])->name('ipd-charge.ipdChargeSubmit');
Route::post('/ipd-charge-view',[IpdinController::class,'viewIpdCharge'])->name('ipd-charge.viewIpdCharge');
Route::post('/ipd-charge-edit',[IpdinController::class,'getIpdChargeData'])->name('ipd-charge.getIpdChargeData');
Route::post('/ipd-charge-update',[IpdinController::class,'ipdChargeDataUpdate'])->name('ipd-charge.ipdChargeDataUpdate');
Route::post('/ipd-charge-delete',[IpdinController::class,'ipdChargeDataDelete'])->name('ipd-charge.ipdChargeDataDelete');

Route::post('/ipd-vital-add',[IpdinController::class,'ipdVItalSubmit'])->name('ipd-vital.ipdVItalSubmit');
Route::post('/ipd-vital-view',[IpdinController::class,'viewIpdVital'])->name('ipd-vital.viewIpdVital');
Route::post('/ipd-vital-data',[IpdinController::class,'getIpdVitalData'])->name('ipd-vital.getIpdVitalData');
Route::post('/ipd-vital-update',[IpdinController::class,'ipdVItalDataUpdate'])->name('ipd-vital.ipdVItalDataUpdate');
Route::post('/ipd-vital-delete',[IpdinController::class,'ipdVitalDataDelete'])->name('ipd-vital.ipdVitalDataDelete');

Route::post('/ipd-nurse-note-add',[IpdinController::class,'ipdNurseNoteSubmit'])->name('ipd-nurse.ipdNurseNoteSubmit');
Route::post('/ipd-nurse-note-view',[IpdinController::class,'viewIpdNurseNote'])->name('ipd-nurse.viewIpdNurseNote');
Route::post('/ipd-nurse-note-data',[IpdinController::class,'getIpdNurseNoteData'])->name('ipd-nurse.getIpdNurseNoteData');
Route::post('/ipd-nurse-note-update',[IpdinController::class,'ipdNurseNoteDataUpdate'])->name('ipd-nurse.ipdNurseNoteDataUpdate');
Route::post('/ipd-nurse-note-delete',[IpdinController::class,'ipdNurseDataDelete'])->name('ipd-nurse.ipdNurseDataDelete');

Route::get('/emergency',[EmergencyController::class,'index'])->name('emergency.index');
Route::get('/emergency-details/{id}',[EmergencyController::class,'emergencyDetails']);
Route::post('/emergency-patient-add',[EmergencyController::class,'addPatient'])->name('emergency-addPatient');
Route::post('/emergency-patient-view',[EmergencyController::class,'viewPatients'])->name('emergency-viewPatients');
Route::post('/emergency-patient-movetoipd',[EmergencyController::class,'moveToIpdStatus'])->name('emergency.moveToIpdStatus');
Route::post('/emergency-patient-discharge',[EmergencyController::class,'patientDischargeStatusE'])->name('emergency.patientDischargeStatusE');
Route::post('/emergency-patient-data',[EmergencyController::class,'getEmergencyPatientData'])->name('emergency-getEmergencyPatientData');
Route::post('/emergency-patient-update',[EmergencyController::class,'emergencyPatientDataUpdate'])->name('emergency-emergencyPatientDataUpdate');
Route::post('/emergency-patient-delete',[EmergencyController::class,'emergencyPatientDataDelete'])->name('emergency-emergencyPatientDataDelete');
Route::post('/emergency-visit-add',[EmergencyController::class,'emergencyVisitSubmit'])->name('emergency-visit.emergencyVisitSubmit');
Route::post('/emergency-visit-view',[EmergencyController::class,'viewEmergencyVisit'])->name('emergency-visit.viewEmergencyVisit');
Route::post('/emergency-visit-data',[EmergencyController::class,'getEmergencyVisitData'])->name('emergency-visit.getEmergencyVisitData');
Route::post('/emergency-visit-update',[EmergencyController::class,'emergencyVisitDataUpdate'])->name('emergency-visit.emergencyVisitDataUpdate');
Route::post('/emergency-visit-delete',[EmergencyController::class,'emergencyVisitDataDelete'])->name('emergency-visit.emergencyVisitDataDelete');

Route::post('/emergency-medicine-dose-add',[EmergencyController::class,'emergencyMedDataAdd'])->name('emergency-med.emergencyMedDataAdd');
Route::post('/emergency-medicine-dose-view',[EmergencyController::class,'viewEmergencyMedDose'])->name('emergency-med.viewEmergencyMedDose');
Route::post('/emergency-medicine-dose-data',[EmergencyController::class,'getEmergencyMedDoseDetails'])->name('emergency-med.getEmergencyMedDoseDetails');
Route::post('/emergency-medicine-dose-update',[EmergencyController::class,'emergencyMedDataUpdate'])->name('emergency-med.emergencyMedDataUpdate');
Route::post('/emergency-medicine-dose-delete',[EmergencyController::class,'emergencyMedDoseDataDelete'])->name('emergency-med.emergencyMedDoseDataDelete');

Route::post('/emergency-lab-data-add',[EmergencyController::class,'emergencyLabSubmit'])->name('emergency-lab.emergencyLabSubmit');
Route::post('/emergency-lab-data-view',[EmergencyController::class,'viewEmergencyLabData'])->name('emergency-lab.viewEmergencyLabData');
Route::post('/emergency-lab-data',[EmergencyController::class,'getEmergencyLabData'])->name('emergency-lab.getEmergencyLabData');
Route::post('/emergency-lab-details',[EmergencyController::class,'getEmergencyLabDetails'])->name('emergency-lab.getEmergencyLabDetails');
Route::post('/emergency-lab-update',[EmergencyController::class,'emergencyLabUpdateData'])->name('emergency-lab.emergencyLabUpdateData');
Route::post('/emergency-lab-delete',[EmergencyController::class,'emergencyLabDataDelete'])->name('emergency-lab.emergencyLabDataDelete');

Route::post('/emergency-charge-add',[EmergencyController::class,'emergencyChargeSubmit'])->name('emergency-charge.emergencyChargeSubmit');
Route::post('/emergency-charge-view',[EmergencyController::class,'viewEmergencyCharge'])->name('emergency-charge.viewEmergencyCharge');
Route::post('/emergency-charge-data',[EmergencyController::class,'getEmergencyChargeData'])->name('emergency-charge.getEmergencyChargeData');
Route::post('/emergency-charge-update',[EmergencyController::class,'emergencyChargeDataUpdate'])->name('emergency-charge.emergencyChargeDataUpdate');
Route::post('/emergency-charge-delete',[EmergencyController::class,'emergencyChargeDataDelete'])->name('emergency-charge.emergencyChargeDataDelete');

Route::post('/emergency-nurse-note-view',[EmergencyController::class,'viewEmergencyNurseNote'])->name('emergency-nurse.viewEmergencyNurseNote');
Route::post('/emergency-nurse-note-add',[EmergencyController::class,'emergencyNurseNoteSubmit'])->name('emergency-nurse.emergencyNurseNoteSubmit');
Route::post('/emergency-nurse-note-data',[EmergencyController::class,'getEmergencyNurseNoteData'])->name('emergency-nurse.getEmergencyNurseNoteData');
Route::post('/emergency-nurse-note-update',[EmergencyController::class,'emergencyNurseNoteDataUpdate'])->name('emergency-nurse.emergencyNurseNoteDataUpdate');
Route::post('/emergency-nurse-note-delete',[EmergencyController::class,'emergencyNurseDataDelete'])->name('emergency-nurse.emergencyNurseDataDelete');

Route::post('/emergency-vital-view',[EmergencyController::class,'viewEmergencyVital'])->name('emergency-vital.viewEmergencyVital');
Route::post('/emergency-vital-add',[EmergencyController::class,'emergencyVItalSubmit'])->name('emergency-vital.emergencyVItalSubmit');
Route::post('/emergency-vital-data',[EmergencyController::class,'getEmergencyVitalData'])->name('emergency-vital.getEmergencyVitalData');
Route::post('/emergency-vital-update',[EmergencyController::class,'emergencyVitalDataUpdate'])->name('emergency-vital.emergencyVitalDataUpdate');
Route::post('/emergency-vital-delete',[EmergencyController::class,'emergencyVitalDataDelete'])->name('emergency-vital.emergencyVitalDataDelete');


Route::post('/common-medicine-name',[CommonController::class,'getMedicineName'])->name('common.getMedicineName');



});


