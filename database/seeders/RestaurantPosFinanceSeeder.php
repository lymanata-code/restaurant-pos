<?php

namespace Database\Seeders;

class RestaurantPosFinanceSeeder extends AbstractRestaurantPosSeeder
{
    protected function seed(): void
    {
        $mainBranch = $this->branch('MAIN');
        $managerUser = $this->find('users', ['username' => 'manager.main']);
        $cashMethod = $this->find('payment_methods', ['restaurant_id' => null, 'code' => 'CASH']);

        $cashAccount = $this->row('accounts', ['account_code' => '1000'], [
            'name' => 'សាច់ប្រាក់ក្នុងដៃ',
            'account_type' => 'asset',
            'is_active' => true,
        ]);

        $expenseAccount = $this->row('accounts', ['account_code' => '5000'], [
            'name' => 'ចំណាយប្រតិបត្តិការ',
            'account_type' => 'expense',
            'is_active' => true,
        ]);

        $expense = $this->row('expenses', ['expense_no' => 'EXP-20260511-0001'], [
            'restaurant_id' => $mainBranch->id,
            'account_id' => $expenseAccount->id,
            'payment_method_id' => $cashMethod->id,
            'title' => 'បញ្ចូលហ្គាសចង្ក្រាន',
            'amount' => 25,
            'expense_date' => $this->now()->toDateString(),
            'reference_no' => 'BILL-9981',
            'description' => 'ចំណាយហ្គាសសម្រាប់ផ្ទះបាយសាខាចម្បង។',
            'created_by' => $managerUser->id,
        ]);

        $journal = $this->row('journal_entries', ['journal_no' => 'JV-20260511-0001'], [
            'restaurant_id' => $mainBranch->id,
            'journal_date' => $this->now()->toDateString(),
            'reference_type' => 'App\\Models\\Expense',
            'reference_id' => $expense->id,
            'description' => 'កត់ត្រាចំណាយហ្គាសចង្ក្រាន',
            'created_by' => $managerUser->id,
        ]);

        $this->row('journal_entry_lines', ['journal_entry_id' => $journal->id, 'account_id' => $expenseAccount->id, 'debit' => 25], [
            'credit' => 0,
            'description' => 'កត់ត្រាចំណាយដើម',
        ]);

        $this->row('journal_entry_lines', ['journal_entry_id' => $journal->id, 'account_id' => $cashAccount->id, 'credit' => 25], [
            'debit' => 0,
            'description' => 'កាត់សាច់ប្រាក់ចេញ',
        ]);
    }
}
