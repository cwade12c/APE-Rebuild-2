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
    private $staticParameters = array();
    private $validations = array();
    private $requiredLogin = true;
    private $allowedAccountTypes = array();
    private $accountValidation = null;
    private $accountValidationParameterNames = array();
    private $actualExecute = null;

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
                'name'     => strtolower($name),
                'type'     => strtolower($type),
                'default'  => $default,
                'optional' => true
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
                'name'     => strtolower($name),
                'type'     => strtolower($type),
                'optional' => false
            )
        );
    }

    /**
     * Register a static parameter
     *
     * @param string $name
     * @param        $value
     */
    protected function registerStaticParameter(string $name, $value)
    {
        $this->staticParameters[strtolower($name)] = $value;
    }

    /**
     * Register a validation necessary
     *
     * @param              $fncCallable
     * @param array|string $input parameter name, or array of names
     *
     * @internal param array|string $fncName
     */
    protected function registerValidation($fncCallable, $input)
    {
        if (!is_callable($fncCallable, false, $callableName)) {
            throw new InvalidArgumentException(
                'Validation callable given is not valid'
            );
        }

        $parameterNames = $this->getValidationInputNames($input);

        array_push(
            $this->validations, array(
                "input" => $parameterNames,
                "fnc"   => $callableName
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
    private function getValidationInputNames($input)
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

    /**
     * If a login is required for the operation
     *
     * @param bool $require
     */
    protected function requireLogin(bool $require)
    {
        $this->requiredLogin = $require;
    }

    /**
     * Require the account accessing the operation to have this type
     *
     * @param int $type
     */
    protected function requiredAccountType(int $type)
    {
        $this->setAllowedAccountTypes(array($type));
    }

    /**
     * Allow the following account types to access this operation
     *
     * @param array $types
     */
    protected function setAllowedAccountTypes(array $types)
    {
        $this->validateTypeArray($types);
        $this->requiredLogin = true;
        $this->allowedAccountTypes = $types;
    }

    /**
     * Helper method for setAllowedAccountTypes
     * To validate the types given
     *
     * @param array $types
     */
    private function validateTypeArray(array $types)
    {
        foreach ($types as $i => $type) {
            if (gettype($type) != 'integer') {
                $typeOf = gettype($type);
                throw new InvalidArgumentException(
                    "Invalid type index({$i}), type: ({$typeOf})"
                );
            }
            if ($type < 0) {
                throw new InvalidArgumentException(
                    "Invalid type value at index({$i}): {$type}"
                );
            }
        }
    }

    /**
     * Register a callable to be used against the account ID executing
     * Note: the function will execute even if login is not required,
     * it should accept null values in that case.
     *
     * @param mixed        $validationCallable
     * @param array|string $parameterNames names of parameters to pass
     */
    protected function registerAccountIDValidation($validationCallable,
        $parameterNames = null
    ) {
        if (!is_callable($validationCallable, false, $callableName)) {
            throw new InvalidArgumentException('Callable given is not valid');
        }

        if ($parameterNames == null) {
            $parameterNames = array();
        } elseif (gettype($parameterNames) == 'string') {
            $parameterNames = array(strtolower($parameterNames));
        } elseif (gettype($parameterNames) == 'array') {
            $parameterNames = array_map('strtolower', $parameterNames);
        } else {
            throw new InvalidArgumentException('Invalid parameter names given');
        }

        $registeredParameterKeys = array_column($this->parameters, 'name');
        $staticParameterKeys = array_keys($this->staticParameters);
        $registeredParameterKeys = array_merge(
            $registeredParameterKeys, $staticParameterKeys
        );
        foreach ($parameterNames as $i => $name) {
            if (!in_array($name, $registeredParameterKeys)) {
                throw new InvalidArgumentException(
                    "Parameter name({$i}) \"{$name}\""
                );
            }
        }

        $this->accountValidation = $callableName;
        $this->accountValidationParameterNames = $parameterNames;
    }

    /**
     * Register the function to execute
     * can be a function name or array(object, name) for a method (static)
     *
     * @param $executionCallable
     */
    protected function registerExecution($executionCallable)
    {
        if (!is_callable($executionCallable, false, $callableName)) {
            throw new InvalidArgumentException('Callable given is not valid');
        }

        $this->actualExecute = $callableName;
    }

    /**
     * Public method for executing the operation
     *
     * @param array       $args      associate array of inputs
     * @param string|null $accountID ID of account requesting
     *
     * @return array|Exception response of operation execution
     * @throws Exception
     */
    public function execute(array $args, string $accountID = null)
    {
        $this->validateExecutionArguments($args);
        $this->executeValidations($args);
        $this->validateAccountID($args, $accountID);

        $errorMessage = null;

        if ($this->actualExecute == null) {
            throw new LogicException('Execution callable not set');
        }

        try {
            $data = call_user_func_array($this->actualExecute, $args);

            $this->validateReturn($data);

            return $data;
        } catch (Exception $e) {
            $errorMessage = $e;
        }

        throw new Exception($errorMessage);
    }

    /**
     * Helper method to validate an account ID executing an operation
     *
     * @param array       $args
     * @param string|null $accountID
     */
    private function validateAccountID(array $args, string $accountID = null)
    {
        if (!$this->requiredLogin) {
            return;
        }

        // standard ID check
        if ($accountID == null) {
            throw new InvalidArgumentException("Null account id");
        }

        validateAccountExists($accountID);

        $type = getAccountType($accountID);
        $this->validateAccountType($type);

        $this->validateAccountCall($accountID, $args);
    }

    /**
     * Validate the account type passes required values
     * Helper method for validateAccountID
     *
     * @param int $type
     */
    private function validateAccountType(int $type)
    {
        if (empty($this->allowedAccountTypes)) {
            return;
        }

        foreach ($this->allowedAccountTypes as $allowedType) {
            if (typeHas($type, $allowedType)) {
                return;
            }
        }
        throw new InvalidArgumentException(
            "Account type ({$type}) does not match any acceptable types"
        );
    }

    /**
     * Run the account ID and args against the set account validation
     * Helper method for validateAccountID
     *
     * @param string $accountID
     * @param array  $args
     */
    private function validateAccountCall(string $accountID, array $args)
    {
        // run account validation function
        if ($this->accountValidation == null) {
            return;
        }

        $validateArgs = array($accountID);
        foreach ($this->accountValidationParameterNames as $parameterName) {
            if (isset($args[$parameterName])) {
                array_push($validateArgs, $args[$parameterName]);
            } elseif (isset($this->staticParameters[$parameterName])) {
                array_push(
                    $validateArgs, $this->staticParameters[$parameterName]
                );
            } else {
                throw new LogicException(
                    "Missing argument \"$parameterName\" for validation"
                );
            }
        }

        try {
            if (!call_user_func_array($this->accountValidation, $validateArgs)) {
                throw new InvalidArgumentException(
                    'False from user validation'
                );
            }
        } catch (Exception $e) {
            throw new InvalidArgumentException(
                "Account ID ({$accountID}) failed operation account validation"
            );
        }
    }

    /**
     * Helper method to validate the arguments received to run an operation
     *
     * @param array $args
     */
    private function validateExecutionArguments(array &$args)
    {
        $finalArgs = array();

        foreach ($this->parameters as $parameter) {
            foreach ($args as $name => $value) {
                $name = strtolower($name);
                if ($name == $parameter['name']) {
                    $this->validateArgument($parameter, $value);
                    $finalArgs[$name] = $value;
                    break;
                }
            }
        }

        $args = $finalArgs;
    }

    /**
     * Helper method for validateExecutionArguments()
     * to validate an argument value against a parameter
     *
     * @param array $parameter
     * @param       $value
     */
    private function validateArgument(array $parameter, &$value)
    {
        if ($value == null && $parameter['optional']) {
            $value = $parameter['default'];
            return;
        }

        $name = $parameter['name'];

        if (gettype($value) != $parameter['type']) {
            $type = gettype($value);
            $expectedType = $parameter['type'];
            throw new InvalidArgumentException(
                "Argument ({$name}) does not match expected type {$expectedType}, got {$type}"
            );
        }


    }

    /**
     * Helper method to run the validations set
     *
     * @param array $args
     */
    private function executeValidations(array $args)
    {
        foreach ($this->validations as $validation) {
            $validationArgs = array();
            foreach ($validation['input'] as $argName) {
                if (isset($args[$argName])) {
                    array_push($validationArgs, $args[$argName]);
                } elseif (isset($this->staticParameters[$argName])) {
                    array_push(
                        $validationArgs, $this->staticParameters[$argName]
                    );
                } else {
                    throw new LogicException(
                        "Missing argument \"$argName\" for validation"
                    );
                }
            }

            $name = $validation['fnc'];
            if (!call_user_func_array($name, $validationArgs)) {
                throw new InvalidArgumentException(
                    "Validation {$name} unknown error"
                );
            }

        }
    }

    /**
     * Helper method to validate the return data for an execute
     *
     * @param $data
     */
    private function validateReturn($data)
    {
        if ($data == null) {
            throw new RuntimeException('Execute returned null value');
        }
        if (gettype($data) != 'array') {
            $type = gettype($data);
            throw new RuntimeException(
                "Execute returned non array value ({$type})"
            );
        }
    }

}