<?php
/**
 * Created by PhpStorm.
 * User: huijiewei
 * Date: 2018/7/25
 * Time: 22:36
 */

namespace app\core\commands;

use app\core\helpers\StringHelper;
use app\core\models\shop\ShopCategory;
use app\core\models\user\User;
use Faker\Factory;
use yii\console\Controller;

class FakeController extends Controller
{
    /**
     * @param int $count 要生成的用户数量
     */
    public function actionUsers($count = 10)
    {
        $faker = $this->getFaker();
        $createFromNameList = User::createdFromNameList();

        for ($i = 0; $i < $count; $i++) {
            $fakePhone = $faker->phoneNumber;

            if (User::find()->where(['phone' => $fakePhone])->exists()) {
                continue;
            }

            $user = new User();
            $user->phone = $fakePhone;
            $user->password = $faker->password;
            $user->passwordRepeat = $user->password;
            $user->display = $faker->name;
            $user->createdIp = $faker->ipv4;
            $user->createdFrom = array_rand($createFromNameList, 1);

            if (rand(1, 100) > 9) {
                $randomAvatar = StringHelper::startsTrim(rand(100001, 100677) . '', '1');
                $user->avatar = "https://yuncars-other.oss-cn-shanghai.aliyuncs.com/" .
                    "boilerplate/avatar/a$randomAvatar.png@!avatar";
            }

            $user->save(false);

            $this->stdout("生成随机用户: $fakePhone 成功\n");
        }
    }

    private function getFaker()
    {
        return Factory::create('zh_CN');
    }

    public function actionShopCategory($name, $parentId = 0)
    {
        if ($parentId > 0 && !ShopCategory::find()->where(['id' => $parentId])->exists()) {
            $this->stdout('分类不存在：' . $parentId);
            return;
        }

        $shopCategory = new ShopCategory();
        $shopCategory->name = $name;
        $shopCategory->parentId = $parentId;

        $shopCategory->save(false);

        $this->stdout('分类添加成功');
    }

    public function actionTest()
    {
        ShopCategory::rebuildClosureTable();
    }
}
