<?php

namespace App\Http\Controllers\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Redirect;

class GatwickController extends \App\Http\Controllers\BaseController
{
    public function getIndex()
    {
        $account = $this->account;

        $groupone = $this->account->networkDataAtc()
            ->whereBetween('connected_at', [Carbon::now()->subMonth(3), Carbon::now()])
            ->where(function ($callsigns) {
                $callsigns->where('callsign', 'like', 'EGCC_%')
                    ->orWhere('callsign', 'like', 'EGPH_%')
                    ->orWhere('callsign', 'like', 'EGSS_%')
                    ->orWhere('callsign', 'like', 'EGGP_%');
            })->get()
            ->sum('minutes_online');
        $g1 = round($groupone / 60, 1);

        $grouptwo = $this->account->networkDataAtc()
            ->whereBetween('connected_at', [Carbon::now()->subMonth(3), Carbon::now()])
            ->where(function ($callsigns) {
                $callsigns->where('callsign', 'like', 'EGPF_%')
                    ->orWhere('callsign', 'like', 'EGBB_%')
                    ->orWhere('callsign', 'like', 'EGGD_%')
                    ->orWhere('callsign', 'like', 'EGGW_%');
            })->get()
            ->sum('minutes_online');
        $g2 = round($grouptwo / 60, 1);

        $groupthree = $this->account->networkDataAtc()
            ->whereBetween('connected_at', [Carbon::now()->subMonth(3), Carbon::now()])
            ->where(function ($callsigns) {
                $callsigns->where('callsign', 'like', 'EGJJ_%')
                    ->orWhere('callsign', 'like', 'EGAA_%')
                    ->orWhere('callsign', 'like', 'EGNT_%')
                    ->orWhere('callsign', 'like', 'EGNX_%');
            })->get()
            ->sum('minutes_online');
        $g3 = round($groupthree / 60, 1);

        if ($account->qualificationAtc->isOBS) {
            return Redirect::back()
                ->withError('Only S1 rated controllers are eligible for a Gatwick Ground endorsement.');
        } elseif(!$account->qualificationAtc->isS1) {
            return Redirect::back()
                ->withError('You hold a controller rating above S1 and do not require an endorsement to control at Gatwick.');
        }

        return $this->viewMake('controllers.gatwick')
            ->with('groupone', $g1)
            ->with('grouptwo', $g2)
            ->with('groupthree', $g3)
            ->with('divisionmember', $account->primary_state->isDivision);
    }
}
