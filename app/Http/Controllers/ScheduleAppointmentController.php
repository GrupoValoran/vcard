<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateScheduleAppointmentRequest;
use App\Models\Appointment;
use App\Models\ScheduleAppointment;
use App\Models\Vcard;
use App\Repositories\AppointmentRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ScheduleAppointmentController extends AppBaseController
{
    /**
     * @param CreateScheduleAppointmentRequest $request
     *
     * @return mixed
     */
    public function store(CreateScheduleAppointmentRequest $request)
    {

        $input = $request->all();

        try {
            DB::beginTransaction();
            $vcard = Vcard::with('tenant.user')->where('id', $input['vcard_id'])->first();
            $input['toName'] = $vcard->fullName > 1 ? $vcard->fullName : $vcard->tenant->user->fullName;
            $input['vcard_name'] = $vcard->name;
            $userId = $vcard->tenant->user->id;

            //Stripe
            if (isset($input['payment_method'])) {
                if ($input['payment_method'] == Appointment::STRIPE) {
                    /** @var AppointmentRepository $repo */
                    $repo = App::make(AppointmentRepository::class);

                    $result = $repo->userCreateSession($userId, $vcard, $input);

                    DB::commit();

                    return $this->sendResponse([
                        'payment_method' => $input['payment_method'],
                        $result,
                    ], 'Stripe session created successfully.');
                }

                //PayPal
                if ($input['payment_method'] == Appointment::PAYPAL) {
                    /** @var PaypalController $payPalCont */
                    $payPalCont = App::make(PaypalController::class);

                    $result = $payPalCont->userOnBoard($userId, $vcard, $input);

                    DB::commit();

                    return $this->sendResponse([
                        'payment_method' => $input['payment_method'],
                        $result,
                    ], 'Paypal session created successfully.');
                }
            }

            /** @var AppointmentRepository $appointmentRepo */
            $appointmentRepo = App::make(AppointmentRepository::class);
            $vcardEmail = is_null($vcard->email) ? $vcard->tenant->user->email : $vcard->email;
            $appointmentRepo->appointmentStoreOrEmail($input, $vcardEmail);

            DB::commit();
            setLocalLang(getLocalLanguage());
            
            return $this->sendSuccess(__('messages.placeholder.appointment_created'));
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }
    }

    /**
     *
     * @return Application|Factory|View
     */
    public function appointmentsList()
    {

        return view('appointment.list');
    }

    /**
     * @param Request $request
     *
     *
     * @return Application|Factory|View
     */
    public function appointmentCalendar(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $data = $this->getCalendar();

            return $this->sendResponse($data, 'Appointment calendar data retrieved successfully.');
        }

        return view('appointment.appointment-calendar');
    }

    /**
     *
     * @return array
     */
    public function getCalendar()
    {
        /** @var ScheduleAppointment $appointment */
        $appointments = ScheduleAppointment::whereHas('vcard', function ($q) {
            $q->where('tenant_id', getLogInTenantId());
        })->get();

        $data = [];
        $count = 0;
        foreach ($appointments as $key => $appointment) {
            $startTime = date("h:i A", strtotime($appointment->from_time));
            $endTime =  date("h:i A", strtotime($appointment->to_time));
            $start = Carbon::createFromFormat('Y-m-d h:i A',
                date('Y-m-d', strtotime($appointment->date)).' '.$startTime);
            $end = Carbon::createFromFormat('Y-m-d h:i A', date('Y-m-d', strtotime($appointment->date)).' '.$endTime);
            $data[$key]['id'] = $appointment->id;
            if (getUserSettingValue('time_format',getLogInUserId()) == 1){
                $data[$key]['startDateTime'] = $start->format('jS M, Y - H:i');
                $data[$key]['endDateTime'] = $end->format('jS M, Y - H:i');
                $data[$key]['title'] = date("H:i", strtotime($appointment->from_time)).'-'.date("H:i", strtotime($appointment->to_time));
            }else {
                $data[$key]['startDateTime'] = $start->format('jS M, Y - h:i A');
                $data[$key]['endDateTime'] = $end->format('jS M, Y - h:i A');
                $data[$key]['title'] = date("h:i A", strtotime($startTime)).'-'.date("h:i A", strtotime($endTime));

            }
            $data[$key]['name'] = $appointment->name;
            $data[$key]['email'] = $appointment->email;
            $data[$key]['phone'] = is_null($appointment->phone) ? 'N/A' : $appointment->phone;
            $data[$key]['vcardName'] = $appointment->vcard->name;
            $data[$key]['start'] = $start->toDateTimeString();
            $data[$key]['description'] = $appointment->vcard->description;
            $data[$key]['status'] = $appointment->vcard->status;
            $data[$key]['end'] = $end->toDateTimeString();
            $data[$key]['color'] = '#FFF';
            $data[$key]['className'] = [getStatusClassName($appointment->vcard->status),];
        }

        return $data;
    }
    /**
     * @param ScheduleAppointment $appointment
     *
     * @return mixed
     */
    public function appointmentsUpdate(ScheduleAppointment $appointment){
 
     $appointments = ScheduleAppointment::findOrFail($appointment->id);

        $appointments->update([
                          'status' => ScheduleAppointment::COMPLETED,
                      ]);
        
        return $this->sendSuccess(__('messages.flash.plan_status'));
    }
}
