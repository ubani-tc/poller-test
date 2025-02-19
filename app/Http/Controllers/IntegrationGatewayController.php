<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class IntegrationGatewayController extends Controller
{
    public function gateway(Request $request)
    {
        Log::info($request);
        $channelName = $request->input('channelName');
        $action = $request->input('action');
        $data = $request->input('data');

        switch ($action) {
            case 'getVirtualAccountTransactionsByDate':
                return $this->getVirtualAccountTransactionsByDate($channelName, $data);

            case 'getTransactionBySessionId':
                return $this->getTransactionBySessionId($channelName, $data);

            case 'getAccountStatement':
                return $this->getAccountStatement($channelName, $data);

            case 'getVirtualAccountTransactions':
                return $this->getVirtualAccountTransactions($channelName, $data);

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action'
                ], 400);
        }
    }

    private function getVirtualAccountTransactionsByDate($channelName, $data)
    {
        // Validate date format based on channel
        if ($channelName === 'wema') {
            // Expecting DD/MM/YYYY
            if (!isset($data['startDate']) || !$this->isValidDateFormat($data['startDate'], 'd/m/Y')) {
                return response()->json(['success' => false, 'message' => 'Invalid date format. Expected DD/MM/YYYY'], 400);
            }
        } else {
            // Expecting YYYY-MM-DD
            if (!isset($data['startDate']) || !$this->isValidDateFormat($data['startDate'], 'Y-m-d')) {
                return response()->json(['success' => false, 'message' => 'Invalid date format. Expected YYYY-MM-DD'], 400);
            }
        }

        return response()->json([
            "success" => true,
            "data" => [
                [
                    "id" => 0,
                    "amount" => 25000,
                    "narration" => "FT//1483927185/NXG :TRFFRM TOCHUKWU CHIMEBUKA UBANI TO FIN-TOCHUKWU UBANI",
                    "transactionType" => "C",
                    "transactionref" => "09FG250219153922436QTA38H",
                    "availableBalance" => 0,
                    "transactionDate" => "2025-02-19",
                    "nubanAccount" => "3000***151",
                    "transID" => "ETZ-09FG250219153913956GF0FZI",
                    "destinationBankcode" => "103",
                    "nubanAccountName" => null,
                    "virtualAccount" => "3998348941",
                    "virtualAccountName" => "FIN-TOCHUKWU UBANI",
                    "sourceBankCode" => "044",
                    "sourceAccount" => "1483927185",
                    "sourceBankName" => "Globus Bank",
                    "sourceAccountName" => "FIN-TOCHUKWU UBANI",
                    "sessionId" => "ETZ-09FG250219153913956GF0FZI",
                    "paymentStatus" => "Complete",
                    "retries" => 0
                ]
            ]
        ]);
    }

    private function getTransactionBySessionId($channelName, $data)
    {
        // Validate session ID key based on channel
        $sessionIdKey = $channelName === 'wema' ? 'sessionid' : 'sessionId';
        if (!isset($data[$sessionIdKey])) {
            return response()->json([
                'success' => false,
                'message' => "Missing {$sessionIdKey}"
            ], 400);
        }

        return response()->json([
            "success" => true,
            "data" => [
                "result" => [
                    [
                        "id" => 0,
                        "amount" => 25000,
                        "narration" => "FT//1483927185/NXG :TRFFRM TOCHUKWU CHIMEBUKA UBANI TO FIN-TOCHUKWU UBANI",
                        "transactionType" => "C",
                        "transactionref" => "09FG250219153922436QTA38H",
                        "availableBalance" => 0,
                        "transactionDate" => "2025-02-19",
                        "nubanAccount" => "3000***151",
                        "transID" => "ETZ-09FG250219153913956GF0FZI",
                        "destinationBankcode" => "103",
                        "nubanAccountName" => null,
                        "virtualAccount" => "3998348941",
                        "virtualAccountName" => "FIN-TOCHUKWU UBANI",
                        "sourceBankCode" => "044",
                        "sourceAccount" => "1483927185",
                        "sourceBankName" => "Access Bank PLC",
                        "sourceAccountName" => "FIN-TOCHUKWU UBANI",
                        "sessionId" => "ETZ-09FG250219153913956GF0FZI",
                        "paymentStatus" => "Complete",
                        "retries" => 0
                    ]
                ],
                "responseCode" => "00",
                "responseMessage" => "Successful"
            ]
        ]);
    }

    private function getAccountStatement($channelName, $data)
    {
        // Validate date format for Wema (DD/MM/YYYY)
        if ($channelName === 'wema') {
            if (!isset($data['startDate']) || !$this->isValidDateFormat($data['startDate'], 'd/m/Y')) {
                return response()->json(['success' => false, 'message' => 'Invalid date format. Expected DD/MM/YYYY'], 400);
            }
        }

        return response()->json([
            "success" => true,
            "data" => [
                [
                    "ACCOUNTNAME" => "FINCRA COLLECTION ACCOUNT",
                    "RCRE_TIME" => "2025-02-18T23:15:04",
                    "LCHG_TIME" => "2025-02-18T23:15:04",
                    "VFD_DATE" => "2025-02-18T23:15:04",
                    "PSTD_DATE" => "2025-02-18T23:15:04",
                    "ENTRY_DATE" => "2025-02-18T23:15:04",
                    "TRAN_DATE" => "2025-02-19T00:00:00",
                    "VALUE_DATE" => "2025-02-18T00:00:00",
                    "TRANID" => "S70043020",
                    "PARTICULARS" => "NIP:TOCHUKWU CALEB IGWE-8100333684/7944766348/FIN",
                    "TRANREMARKS" => "100004250218221203127497536974",
                    "DR" => null,
                    "CR" => 8000,
                    "BALANCE" => 8000,
                    "PART_TRAN_SRL_NUM" => " 2",
                    "INSTRMNT_NUM" => null,
                    "GL_DATE" => "2025-02-19T00:00:00"
                ]
            ]
        ]);
    }

    private function getVirtualAccountTransactions($channelName, $data)
    {
        // Validate session ID key based on channel
        $sessionIdKey = $channelName === 'wema' ? 'sessionid' : 'sessionId';
        if (!isset($data[$sessionIdKey])) {
            return response()->json([
                'success' => false,
                'message' => "Missing {$sessionIdKey}"
            ], 400);
        }

        return response()->json([
            "success" => true,
            "data" => [
                [
                    "originatoraccountnumber" => "1017692813",
                    "amount" => "24000.00",
                    "originatorname" => "Paystack",
                    "narration" => "NIP:Paystack-7943458595/FINCRA/Valenti/1017692813",
                    "craccountname" => "FINCRA/Valentine Ihezie",
                    "paymentreference" => "",
                    "bankname" => null,
                    "sessionid" => "110006250219172205076781120801",
                    "craccount" => "7943458595",
                    "bankcode" => "110006",
                    "requestdate" => "2/19/2025 6:22:05 PM",
                    "nibssresponse" => "00",
                    "sendresponse" => "00:Okay"
                ]
            ]
        ]);
    }

    private function isValidDateFormat($date, $format)
    {
        $dateObj = DateTime::createFromFormat($format, $date);
        return $dateObj && $dateObj->format($format) === $date;
    }
}
