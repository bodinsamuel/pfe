<?php namespace Custom;

class ApiResponse
{
    private $code = 200;
    private $error = FALSE;

    private $data = [];

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function error($code = 400, $error, $autosend = TRUE)
    {
        $this->error = $error;
        $this->code = $code;

        if ($autosend === TRUE)
            return $this->send();
    }

    public function send()
    {
        $tpl = $this->data;

        $tpl['error'] = $this->error;
        return \Response::json($tpl, $this->code);
    }
}
