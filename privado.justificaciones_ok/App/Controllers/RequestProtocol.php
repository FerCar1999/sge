<?php
interface RequestProtocol {
  
  public function index($request);  
  public function store($request);
  public function edit($request);
  public function update($request);
  public function destroy($request);

}
?>