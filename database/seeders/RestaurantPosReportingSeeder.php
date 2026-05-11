<?php

namespace Database\Seeders;

class RestaurantPosReportingSeeder extends AbstractRestaurantPosSeeder
{
    protected function seed(): void
    {
        $mainBranch = $this->branch('MAIN');
        $adminUser = $this->find('users', ['username' => 'admin']);
        $managerUser = $this->find('users', ['username' => 'manager.main']);
        $waiterUser = $this->find('users', ['username' => 'waiter.main']);
        $deliveryOrder = $this->find('orders', ['order_no' => 'ORD-20260511-0002']);
        $dineInOrder = $this->find('orders', ['order_no' => 'ORD-20260511-0001']);
        $lokLak = $this->find('menu_items', ['item_code' => 'FOOD-001']);

        $this->row('notifications', ['title' => 'ការដឹកជញ្ជូនបានចេញដំណើរ', 'user_id' => $managerUser->id], [
            'restaurant_id' => $mainBranch->id,
            'message' => 'ការបញ្ជាទិញ ORD-20260511-0002 ត្រូវបានប្រគល់ឱ្យអ្នកដឹកជញ្ជូនរួចហើយ។',
            'notification_type' => 'order_new',
            'channel' => 'system',
            'reference_type' => 'App\\Models\\Order',
            'reference_id' => $deliveryOrder->id,
            'read_at' => null,
            'sent_at' => $this->now()->subMinutes(18),
        ]);

        $this->row('report_exports', ['restaurant_id' => $mainBranch->id, 'report_type' => 'sales', 'requested_by' => $managerUser->id], [
            'format' => 'pdf',
            'filters' => $this->json([
                'locale' => 'km',
                'date' => $this->now()->toDateString(),
                'branch' => 'សាខាចម្បង បឹងកេងកង',
            ]),
            'file_path' => 'reports/sales-' . $this->now()->format('Ymd') . '-km.pdf',
            'status' => 'completed',
            'generated_at' => $this->now()->subMinutes(5),
        ]);

        $this->row('audit_logs', ['module' => 'order', 'action' => 'create', 'auditable_type' => 'App\\Models\\Order', 'auditable_id' => $dineInOrder->id], [
            'restaurant_id' => $mainBranch->id,
            'user_id' => $waiterUser->id,
            'old_values' => null,
            'new_values' => $this->json(['order_no' => $dineInOrder->order_no, 'status' => $dineInOrder->status]),
            'reason' => 'កត់ត្រាការបញ្ជាទិញថ្មីនៅម៉ាស៊ីន POS',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder Browser',
            'performed_at' => $this->now()->subHours(2),
        ]);

        $this->row('file_uploads', ['restaurant_id' => $mainBranch->id, 'path' => 'uploads/menu/beef-lok-lak.jpg'], [
            'uploaded_by' => $managerUser->id,
            'uploadable_type' => 'App\\Models\\MenuItem',
            'uploadable_id' => $lokLak->id,
            'disk' => 'public',
            'original_name' => 'lok-lak-kh.jpg',
            'mime_type' => 'image/jpeg',
            'size_bytes' => 145230,
        ]);

        $this->row('backups', ['backup_no' => 'BKP-20260511-0001'], [
            'restaurant_id' => $mainBranch->id,
            'created_by' => $adminUser->id,
            'backup_type' => 'manual',
            'file_path' => 'backups/restaurant-pos-20260511.sql.gz',
            'size_bytes' => 7340032,
            'status' => 'completed',
            'completed_at' => $this->now()->subMinutes(2),
        ]);
    }
}
