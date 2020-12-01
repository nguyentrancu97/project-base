<?php

namespace Modules\Users\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Grades\Transformers\GradesTransformer;
use Modules\Users\Entities\Student;

class StudentTransformer extends TransformerAbstract
{

    /**
     * Include resources without needing it to be requested.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'grades'
    ];

    /**
     * @param Student $student
     * @return array
     */
    public function transform(Student $student)
    {
        return [
            'id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'gender' => $student->gender,
            'address_country' => $student->address_country,
            'address_city' => $student->address_city,
            'call_success' => $student->call_success ?: 0,
            'call_false' => $student->call_false ?: 0,
            'note' => $student->note
        ];
    }

    /**
     * @param Student $student
     * @return \League\Fractal\Resource\Item|null
     */
    public function includeGrades(Student $student)
    {
        if ($student->grades) {
            $grade = $student->grades;
            return $this->item($grade, new GradesTransformer);
        } else {
            return null;
        }
    }

}
