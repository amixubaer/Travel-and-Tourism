<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Income;
use App\Transaction;
use App\Salary;

class AdminAccountsController extends Controller
{
    public function income(){
        $incomes = Income::all();
        return view('admin.incomeList')->with('incomes', $incomes);
    }

    public function transaction(){
        $transactions = Transaction::all();
        return view('admin.transactionList')->with('transactions', $transactions);
    }

    public function salary(){
        $salaries = Salary::where('req', 'Pending')->get();
        return view('admin.ADSalary')->with('salaries', $salaries);
    }

    public function salaryApprove($id){
        $salary = Salary::find($id);
        return view('admin.salaryApprove')->with('salary', $salary);
    }

    public function salaryAdd($id){
        $salary = Salary::find($id);
        $salary->req = 'Approved';
        $salary->save();

        return redirect()->route('AdminAccounts.salary');
    }

    public function salaryDecline($id){
        $salary = Salary::find($id);
        return view('admin.salaryDecline')->with('salary', $salary);
    }

    public function salaryRemove($id){
        $salary = Salary::find($id);
        $salary->req = 'Declined';
        $salary->save();

        return redirect()->route('AdminAccounts.salary');
    }
}
