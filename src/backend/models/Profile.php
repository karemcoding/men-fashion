<?php

namespace backend\models;

use common\models\User;
use common\util\AppHelper;
use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;

class Profile extends Model
{
    const EXTENSIONS = ['jpg', 'png', 'jpeg'];

    public $id;
    public $username;
    public $email;
    public $tel;
    public $name;
    public $password;
    public $newPassword;
    public $confirmPassword;
    public $fileAvatar;
    public $avatar;
    public $role;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'tel', 'name', 'id', 'username'], 'required'],
            [['email'], 'trim'],
            [['email'], 'email'],
            [['email', 'tel'], 'validateDuplicate'],
            [['name', 'password'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 30],
            [['avatar'], 'string'],
            [['newPassword', 'confirmPassword'], 'string', 'min' => 6],
            [['confirmPassword'], 'compare', 'compareAttribute' => 'newPassword'],
            [['email', 'tel'], 'validateDuplicate'],
            [['fileAvatar'], 'file',
                'skipOnEmpty' => true,
                'skipOnError' => true,
                'extensions' => self::EXTENSIONS,
                'maxSize' => 1048576
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'fileAvatar' => Yii::t('common', 'Ảnh đại diện'),
            'role' => Yii::t('common', 'Chức danh'),
            'username'=> Yii::t('common', 'Tên đăng nhập'),
            'name'=> Yii::t('common', 'Tên'),
            'tel' => Yii::t('common', 'Số điện thoại'),
            'password' => Yii::t('common', 'Mật khẩu'),
            'confirmPassword' => Yii::t('common', 'Xác nhận mật khẩu'),
            'newPassword' => Yii::t('common','Mật khẩu mới')
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => [
                'name', 'email', 'tel',
                'password', 'newPassword', 'confirmPassword',
                'fileAvatar', 'avatar'
            ]
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @param $validator
     */
    public function validateDuplicate($attribute, $params, $validator)
    {
        $model = User::find()
            ->andWhere([$attribute => $this->$attribute])
            ->notDeleted();
        if ($this->id != null) {
            $model->andWhere(['<>', 'id', $this->id]);
        }
        if ($model->exists()) {
            $this->addError($attribute, "\"{$this->$attribute}\" has already been taken.");
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($this->password && $this->newPassword && $this->confirmPassword) {
            if ($user->validatePassword($this->password)) {
                $user->setPassword($this->newPassword);
                $user->generateAuthKey();
            } else {
                $this->addError('password',
                    Yii::t('common', "Sai mật khẩu"));
                return false;
            }
        }
        $user->name = $this->name;
        $user->tel = $this->tel;
        $user->email = $this->email;
        if ($this->fileAvatar) {
            $user->avatar = $this->upload();
        } else {
            if (!$this->avatar) {
                $this->deleteOldImage();
                $user->avatar = null;
            }
            if (!empty($user->dirtyAttributes['avatar'])) {
                $user->avatar = $user->oldAttributes['avatar'];
            }
        }
        if ($user->save()) {
            return true;
        } else {
            $this->addErrors($user->errors);
        }
        return false;
    }

    /**
     * @return string|null
     */
    public function upload()
    {
        if (empty($this->fileAvatar)) {
            return null;
        }
        $relativeDir = "public/user/{$this->id}";
        $absoluteDir = Yii::getAlias("@root/$relativeDir");
        $newFileName = uniqid() . '.' . $this->fileAvatar->extension;
        if (!is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0755);
        }
        $file = "{$absoluteDir}/$newFileName";
        $this->deleteOldImage();
        if ($this->fileAvatar->saveAs($file)) {
            $result = "/$relativeDir/$newFileName";
            $this->fileAvatar = null;
            return $result;
        }
        return null;
    }

    /**
     * @return bool
     */
    protected function deleteOldImage()
    {
        if ($oldAvatar = Yii::$app->user->identity->avatar) {
            try {
                return $this->deleteFile($oldAvatar);
            } catch (Exception $ex) {
                return false;
            }
        }
        return false;
    }

    /**
     * @param $filePath
     * @return bool
     */
    protected function deleteFile($filePath)
    {
        $file = Yii::getAlias("@root$filePath");
        if (file_exists($file)) {
            return FileHelper::unlink($file);
        }
        return false;
    }

    /**
     * @return string|null
     */
    public function previewAvatar()
    {
        if ($avatar = Yii::$app->user->identity->avatar) {
            return AppHelper::webHostRoot() . $avatar;
        }
        return null;
    }
}