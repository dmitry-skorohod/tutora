<?php

namespace App\Presenters\Email;

use Route;
use App\UserRequirement;
use App\Presenters\AbstractPresenter;
use Illuminate\Support\Collection;
use App\Transformers\Traits\UserRequirementTrait;

class UserRequirementsEmailPresenter extends AbstractPresenter
{

    use UserRequirementTrait;

    /**
     * Transform the collection into a generic array
     *
     * @param  Collection of UserRequirements
     * @return array
     */
    public function transform(Collection $requirements)
    {
        
        
        // Filter
        $required  = $requirements->where('is_optional', false);
        $incompleteRequirements = $required->where('is_completed', false);

        // Sort
        $incompleteRequirements = $incompleteRequirements->sortBy(function ($requirement) {
            return array_search(
                $requirement->name,
                $this->order
            );
        });

        // Data
        $data = [];
        foreach ($incompleteRequirements as $requirement) {

            $data[] = $this->transformRequirement($requirement);
        }
        // Count filtered

        // Return
        return [
            'data' => $data,
        ];
    }

    /**
     * Transform the item into a generic array
     *
     * @param  UserRequirement $requirement
     * @return array
     */
    public function transformRequirement(UserRequirement $requirement)
    {
        return [
            'id'           => (integer) $requirement->id,
            'name'         => (string)  $requirement->name,
            'section'      => (string)  $requirement->section,
            'title'        => (string)  trans("requirements.{$requirement->name}"),
            'url'          => (string)  $this->getRequirementUrl($requirement),
            'is_js'        => (boolean) $this->isRequirementJs($requirement),
            'is_completed' => (boolean) $requirement->is_completed,
            'is_optional'  => (boolean) $requirement->is_optional,
        ];
    }

    /**
     * Is the requirement js driven?
     *
     * @param  UserRequirement $requirement
     * @return boolean
     */
    protected function isRequirementJs(UserRequirement $requirement)
    {
        $method = camel_case("is_{$requirement->name}_requirement_js");

        if (method_exists($this, $method)) {
            return $this->{$method}($requirement, Route::currentRouteName());
        }

        return false;
    }

    /**
     * Should the given requirement url be triggered via js?
     *
     * @param  UserRequirement $requirement
     * @param  string          $route
     * @return boolean
     */
    protected function isTaglineRequirementJs(
        UserRequirement $requirement,
        $route
    ) {
        return $this->isProfileRequirementJs($route);
    }

    /**
     * Should the given requirement url be triggered via js?
     *
     * @param  UserRequirement $requirement
     * @param  string          $route
     * @return boolean
     */
    protected function isRateRequirementJs(
        UserRequirement $requirement,
        $route
    ) {
        return $this->isProfileRequirementJs($route);
    }

    /**
     * Should the given requirement url be triggered via js?
     *
     * @param  UserRequirement $requirement
     * @param  string          $route
     * @return boolean
     */
    protected function isBioRequirementJs(
        UserRequirement $requirement,
        $route
    ) {
        return $this->isProfileRequirementJs($route);
    }

    /**
     * Should the given requirement url be triggered via js?
     *
     * @param  UserRequirement $requirement
     * @param  string          $route
     * @return boolean
     */
    protected function isProfilePictureRequirementJs(
        UserRequirement $requirement,
        $route
    ) {
        return $this->isProfileRequirementJs($route);
    }

    /**
     * Should the given requirement url be triggered via js?
     *
     * @param  UserRequirement $requirement
     * @param  string          $route
     * @return boolean
     */
    protected function isSubjectsRequirementJs(
        UserRequirement $requirement,
        $route
    ) {
        return $this->isProfileRequirementJs($route);
    }

    /**
     * Should the given requirement url be triggered via js?
     *
     * @param  UserRequirement $requirement
     * @param  string          $route
     * @return boolean
     */
    protected function isQualificationsRequirementJs(
        UserRequirement $requirement,
        $route
    ) {
        return $this->isProfileRequirementJs($route);
    }

    /**
     * Should the given requirement url be triggered via js?
     *
     * @param  UserRequirement $requirement
     * @param  string          $route
     * @return boolean
     */
    protected function isTravelPolicyRequirementJs(
        UserRequirement $requirement,
        $route
    ) {
        return $this->isProfileRequirementJs($route);
    }

    /**
     * Should the given requirement url be triggered via js?
     *
     * @param  UserRequirement $requirement
     * @param  string          $route
     * @return boolean
     */
    protected function isQualifiedTeacherStatusRequirementJs(
        UserRequirement $requirement,
        $route
    ) {
        return $this->isProfileRequirementJs($route);
    }

    /**
     * Should a profile requirement be triggered via js?
     *
     * @param  string $route
     * @return boolean
     */
    protected function isProfileRequirementJs($route)
    {
        return $route === 'tutor.profile.show';
    }

    /**
     * Should the given requirement url be triggered via js?
     *
     * @param  UserRequirement $requirement
     * @param  string          $route
     * @return boolean
     */
    protected function isPaymentDetailsRequirementJs(
        UserRequirement $requirement,
        $route
    ) {
        return $this->isAccountRequirementJs($route);
    }

    /**
     * Should the given requirement url be triggered via js?
     *
     * @param  UserRequirement $requirement
     * @param  string          $route
     * @return boolean
     */
    protected function isIdentificationRequirementJs(
        UserRequirement $requirement,
        $route
    ) {
        return $this->isAccountRequirementJs($route);
    }

    /**
     * Should an account requirement be triggered via js?
     *
     * @param  string $route
     * @return boolean
     */
    protected function isAccountRequirementJs($route)
    {
        return starts_with($route, 'tutor.account');
    }

}
