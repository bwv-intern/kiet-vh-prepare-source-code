<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Utils\MessageUtil;
use App\Utils\ValidateUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Yaml\Yaml;


class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function search(array $params) {

        $users = $this->userRepository->search($params);

        return $users;
    }

    

    public function create($data) {
        $email = $data['email'];
        $password = $data['password'];
        $name = $data['name'];
        $userFlag = $data['user_flg'];
        $dateOfBirth = $data['date_of_birth'] ?? null;
        $phone = $data['phone'] ?? null;
        $address = $data['address'] ?? null;

        $userData = [
            'email' => $email,
            'password' => $password,
            'name' => $name,
            'user_flg' => $userFlag,
            'date_of_birth' => $dateOfBirth ? Carbon::createFromFormat('Y/m/d', $dateOfBirth)->toDateString() : null,
            'phone' => $phone,
            'address' => $address,
        ];

        return $this->userRepository->save(null,$userData,true);

    }

    public function find($id) {
        return $this->userRepository->find($id);
    }

    public function edit($data) {
        $id = $data['id'];
        $email = $data['email'];
        $fullName = $data['fullName'];
        $newPassword = $data['password'];
        $userFlag = $data['userFlag'];
        $date_of_birth = $data['dob'] ?? null;
        $phone = $data['phone'] ?? null;
        $address = $data['address'] ?? null;

        $userData = [
            'email' => $email,
            'name' => $fullName,
            'user_flg' => $userFlag,
            'date_of_birth' => $date_of_birth ? Carbon::createFromFormat('Y/m/d', $date_of_birth)->toDateString() : null,
            'phone' => $phone,
            'address' => $address,
        ];

        if ($newPassword) {
            $userData['password'] = Hash::make($newPassword);
        }

        return $this->userRepository->edit($id, $userData);
    }

    public function delete($id) {
        $founded = $this->userRepository->find($id);
        if ($founded) {
            return $this->userRepository->delete($id);
        }
        return false;
    }

    public function findByEmail($email) {
        return $this->userRepository->findByEmail($email);
    }

    public function insertMany($users) {
        return $this->userRepository->insertMany($users);
    }

    public function editMany($users) {
        return $this->userRepository->editMany($users);
    }


    /*--------------------------------------------------------------------------------------*/
    /*--------------------                  IMPORT CSV                 ---------------------*/
    /*--------------------------------------------------------------------------------------*/

    public function importCsv($filePath) {
        $file = fopen($filePath, 'r');

        $headersYaml = $this->getHeaderFromConfigsYAML();
        $headersCSV = $this->getHeaderCSVFile($file);

        $checkHeader = $this->checkHeader($headersYaml, $headersCSV);
        if (! $checkHeader) {
            fclose($file);

            return [
                'message' => 'WRONG_HEADER',
                'data' => [],
            ];
        }

        $savedUsers = [];
        $editedUsers = [];
        $errorList = [];

        $savedUserEmails = [];

        $rowIndex = 0;

        $keyYaml = $this->getKeyYaml();

        $rules = $this->getValidation();
        while ($row = fgetcsv($file)) {
            $rowIndex++;

            // Check if the number of fields in the row is different from the number of headerCSV
            if (count($row) != count($headersCSV)) {
                return [
                    'message' => 'WRONG_HEADER',
                    'data' => [],
                ];
            }

            $row = array_combine($keyYaml, $row);
            $rules = array_combine($keyYaml, $rules);

            // check email exist in savedUserEmail
            if (in_array($row['email'], $savedUserEmails)) {
                $errorList[] = "Row {$rowIndex} :" . MessageUtil::getMessage('E009', 'Email');
            }

            // validate row
            $errors = $this->validateRow($row, $rules, $rowIndex);

            if (count($errors) > 0) {
                $errorList[] = $errors;
            } else {
                $row['date_of_birth'] = ValidateUtil::convertDateFormat($row['date_of_birth']);

                if ($row['id'] === '') {
                    unset($row['id']);
                    $row['password'] = Hash::make($row['password']);
                    $row['created_by'] = Auth::id();
                    $row['created_at'] = now();
                    $savedUsers[] = $row;
                    // save email
                    $savedUserEmails[] = $row['email'];
                } else {

                    $editedUsers[] = $row;
                }
            }
        }
        fclose($file);
        if (count($errorList) > 0) {
            return [
                'message' => 'ERROR',
                'data' => $errorList,
            ];
        }

        if (count($savedUsers) > 0) {
            $this->insertMany($savedUsers);
        }

        if (count($editedUsers) > 0) {
            $this->editMany($editedUsers);
        }

        return [
            'message' => 'SUCCESS',
            'data' => [],
        ];
    }

    /**
     * Retrieve the header configuration from the YAML file.
     *
     * @return array The header configuration array.
     */
    public function getHeaderFromConfigsYAML() {
        // Get Header of YAML
        $yamlContents = Yaml::parse(file_get_contents(base_path('/config/constants/configImportUser.yaml')));
        $headerConfig = [];
        foreach ($yamlContents['user_import_csv_configs'] as $key => $value) {
            $headerConfig[] = $value['header'];
        }

        return $headerConfig;
    }

    /**
     * Retrieve the header line from a CSV file.
     *
     * @param resource $file The file handle of the CSV file.
     * @return array|null The header line as an array, or null if the file is empty.
     */
    public function getHeaderCSVFile($file) {
        $headerLine = fgetcsv($file);

        return $headerLine;
    }

    /**
     * Compare the headers from a YAML file with the headers from a CSV file.
     *
     * @param array $yamlHeader The header array from the YAML file.
     * @param array $csvHeader The header array from the CSV file.
     * @return bool True if all CSV headers are present in the YAML headers, false otherwise.
     */
    public function checkHeader($yamlHeader, $csvHeader) {
        foreach ($csvHeader as $header) {
            if (! in_array($header, $yamlHeader)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retrieve the validation rules from the YAML file.
     *
     * @return array The validation rules array.
     */
    public function getValidation() {
        $yamlContents = Yaml::parse(file_get_contents(base_path('/config/constants/configImportUser.yaml')));
        $rules = [];
        foreach ($yamlContents['user_import_csv_configs'] as $key => $value) {
            $validConfig = $value['validate'];
            // $rules[] = $this->convertArrToValidateString($validConfig);
            $rules[] = $validConfig;
        }

        return $rules;
    }

    /**
     * Validate a row of data against the given rules.
     *
     * @param array $row The data row to validate.
     * @param array $rules The validation rules to apply.
     * @param int $rowIndex The index of the row being validated.
     * @return array An array of validation error messages, or an empty array if validation passes.
     */
    public function validateRow($row, $rules, $rowIndex) {
        $validator = Validator::make($row, $rules);

        $validator->setCustomMessages($this->messages($rowIndex));

        $validator->after(function ($validator) use ($row, $rowIndex) {
            $email = $row['email'];
            $id = $row['id'] ?? null;

            // Check unique email address
            // 'unique:users'
            $existingUserWithEmail = $this->findByEmail($email);
            if ($existingUserWithEmail && ($id == '' || $existingUserWithEmail->id != intval($id))) {
                $validator->errors()->add('email', "Row {$rowIndex} :" . MessageUtil::getMessage('E009', 'Email'));
            }

            // Check exist user in case editing
            if ($id != '') {
                $existingUserWithId = $this->find($id);
                if (! $existingUserWithId) {
                    $validator->errors()->add('id', "Row {$rowIndex} :" . MessageUtil::getMessage('E015', 'Id'));
                }
            }
        });

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        return [];
    }

    public function messages($rowIndex) {
        $messages = [
            'id.max' => "Row {$rowIndex} :" . MessageUtil::getMessage('E002', 'Full Name', '50', ':count'),

            'email.required' => "Row {$rowIndex} : " . MessageUtil::getMessage('E001', 'Email'),
            'email.email' => "Row {$rowIndex} : " . MessageUtil::getMessage('E004'),
            'email.max' => "Row {$rowIndex} : " . MessageUtil::getMessage('E002', 'Email', '50', ':count'),
            'email.unique' => "Row {$rowIndex} : " . MessageUtil::getMessage('E009', 'Email'),

            'name.required' => "Row {$rowIndex} : " . MessageUtil::getMessage('E001', 'Name'),
            'name.max' => "Row {$rowIndex} : " . MessageUtil::getMessage('E002', 'Name', '50', ':count'),

            'password.required' => "Row {$rowIndex} : " . MessageUtil::getMessage('E001', 'Password'),
            'password.max' => "Row {$rowIndex} : " . MessageUtil::getMessage('E002', 'Password', '255', ':count'),

            'user_flg.required' => "Row {$rowIndex} : " . MessageUtil::getMessage('E001', 'User Flag'),
            'user_flg.numeric' => "Row {$rowIndex} : " . MessageUtil::getMessage('E012', 'User flag', 'number'),
            'user_flg.in' => "Row {$rowIndex} : " . MessageUtil::getMessage('E012', 'User flag', 'number'),

            'date_of_birth.date_format' => "Row {$rowIndex} : " . MessageUtil::getMessage('E012', 'Date of birth', 'date'),
        ];

        return $messages;
    }

    /**
     * Retrieve the keys from the YAML file.
     *
     * @return array The array of keys from the YAML file.
     */
    public function getKeyYaml() {
        $yamlContents = Yaml::parse(file_get_contents(base_path('/config/constants/configImportUser.yaml')));
        $headerConfig = [];
        foreach ($yamlContents['user_import_csv_configs'] as $key => $value) {
            $headerConfig[] = $key;
        }

        return $headerConfig;
    }

}
