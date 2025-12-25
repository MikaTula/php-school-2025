<?php

    declare(strict_types=1);

    namespace App\Http;

    use App\Api\Models\AuthInfoModel;
    use App\Http\Validators\DateValidator;
    use App\Http\Validators\IntValidator;
    use App\Http\Validators\MaxLengthValidator;
    use App\Http\Validators\RequiredValidator;

    abstract class Request
    {
        /** @var array<string, string> */
        public array $validationMessages;

        public ?AuthInfoModel $authInfo = null;

        public ?string $authToken = null;

        public function __construct(
            public readonly string $method,
            public readonly string $path,
            public object $data
        ) {
        }

        abstract public function rules(): array;

        abstract public function getModel(): object;

        public function validate(): bool
        {
            $this->validationMessages = [];

            foreach ($this->rules() as $field => $fieldRules) {
                foreach ($fieldRules as $rule) {
                    switch ($rule) {
                        case 'required':
                            if (!(new RequiredValidator())->isValid($field, $this->data->params)) {
                                $this->validationMessages[$field] = 'Field is required';
                                return false;
                            }
                            break;

                        case 'int':
                            if (!(new IntValidator())->isValid($field, $this->data->params)) {
                                $this->validationMessages[$field] = 'Field must be int';
                                return false;
                            }
                            break;

                        case 'date':
                            if (!(new DateValidator())->isValid($field, $this->data->params)) {
                                $this->validationMessages[$field] = 'Field must be real date in format Y-m-d';
                                return false;
                            }
                            break;

                        case stripos($rule, 'max:') !== false:
                            $maxLength = (int)substr($rule, 4);
                            if (!(new MaxLengthValidator($maxLength))->isValid($field, $this->data->params)) {
                                $this->validationMessages[$field] = 'Field length must be no more than ' . $maxLength;
                                return false;
                            }
                            break;
                    }
                }
            }

            return true;
        }
    }
