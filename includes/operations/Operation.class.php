<?php
/**
 * Base operation class
 *
 * @author         Curran Higgins
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */

abstract class Operation
{
    private $parameters = array();
    private $validations = array();

    /**
     * Register an optional parameter
     *
     * @param string $name
     * @param string $type
     * @param mixed  $default
     */
    protected function registerOptionalParameter(string $name, string $type,
        mixed $default
    ) {
        array_push(
            $this->parameters, array(
                "name"     => strtolower($name),
                "type"     => strtolower($type),
                "default"  => $default,
                "optional" => true
            )
        );
    }

    /**
     * Register a required parameter
     *
     * @param string $name
     * @param string $type
     */
    protected function registerParameter(string $name, string $type)
    {
        array_push(
            $this->parameters, array(
                "name"     => strtolower($name),
                "type"     => strtolower($type),
                "optional" => false
            )
        );
    }

    /**
     * Register a validation necessary
     *
     * @param string       $fncName
     * @param array|string $input parameter name, or array of names
     */
    protected function registerValidation(string $fncName, mixed $input)
    {
        $parameterNames = $this->getValidationInputNames($input);

        array_push(
            $this->validations, array(
                "input"   => $parameterNames,
                "fncName" => $fncName
            )
        );
    }

    /**
     * Helper method for registerValidation to handle being able
     * to accept a string or array of strings for parameter names
     *
     * @param array|string $input
     *
     * @return array
     */
    private function getValidationInputNames(mixed $input)
    {
        $valid = false;
        $inputType = gettype($input);
        $parameterNames = array();

        if ($inputType == 'string') {
            $valid = true;
            array_push($parameterNames, strtolower($input));
        } elseif ($inputType == 'array') {
            $valid = true;
            foreach ($input as $inputIndex) {
                if (gettype($inputIndex) != 'string') {
                    $valid = false;
                    break;
                }
                array_push($parameterNames, strtolower($inputIndex));
            }
        }

        if (!$valid) {
            throw new InvalidArgumentException(
                "Inputs not string or array of strings"
            );
        }

        return $parameterNames;
    }

    // TODO: set account type(s) allowed

    // TODO: register validation function for account ID executing

    // TODO: set that operation requires a login (default: true)

    // TODO: transferring parameters to the hook function
    /*
     * In concrete execute, can use
     *      func_get_args()
     *      associative array as singular argument
     *      call_user_func(), to call
     *          but have to sync up defined parameters and method signature
     * can look into using -
     *  http://php.net/manual/en/reflectionmethod.invoke.php
     *  then just register parameters automatically
     *
     * can offer both, for operations that may allow lots of optional values
     *
     */

    /**
     * Public method for executing the operation
     *
     * @param array $args associate array of inputs
     *
     * @return array
     */
    public function execute(array $args)
    {
        // check login required
        // check account type
        // check parameter validations
        // check account validations

        if (count($args) != count($this->parameters)) {
            return $this->buildResponse(false, "Invalid number of parameters!");
        }

        return $this->buildResponse(true, "OK");
    }

    private function buildResponse(bool $success, string $message)
    {
        return array(
            "data"    => array(),
            "success" => $success,
            "message" => $message
        );
    }
}