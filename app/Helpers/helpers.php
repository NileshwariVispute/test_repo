<?php

/**
 *
 * Description: Build json response
 * Date: 10-July-2020
 * @author Ganesh Suryawanshi
 *
 * @param string $status
 * @param int $msg_code
 * @param array $msg
 * @param array $data
 * @throws Exception
 * @return JSON
 */
function buildResponse($status, $msg_code = 0, $msg = null, $data = null)
{
    $response_array = array(
        'status' => $status,
        'msg_code' => empty($msg_code) ? 0 : $msg_code,
        'msg' => $msg,
        'data' => $data
    );
    Log::info('Response Log: ' . json_encode($response_array));
    return Response()->json($response_array);
}

/**
 *
 * Description: Sanitize extra data from request
 * Date: Date: 10-July-2020
 * @author Ganesh Suryawanshi
 *
 * @param array $input_data
 * @param array $fields
 * @throws Exception
 * @return array $input_data
 */
function sanitizeData($input_data, $fields = [])
{
    try {
        foreach ($input_data as $key => $value) {
            if (! in_array($key, $fields)) {
                unset($input_data[$key]);
            }
        }
        return $input_data;
    } catch (Exception $exception) {
        throw $exception;
    }
}
