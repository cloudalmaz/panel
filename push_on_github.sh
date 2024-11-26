#!/bin/bash

script_dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

get_run_count() {
  count_file="${script_dir}/run_count.txt"
  
  if [ ! -f "$count_file" ]; then
    echo 0 > "$count_file"
  fi
  
  run_count=$(cat "$count_file")
  ((run_count++))
  echo $run_count > "$count_file"
  
  echo $run_count
}

add_files() {
  echo "Добавляю изменённые файлы..."
  git add . > /dev/null 2>&1
}

commit_changes() {
  run_count=$(get_run_count)
  commit_message="V$run_count.0"
  echo "Делаем коммит с сообщением: $commit_message"
  git commit -m "$commit_message" > /dev/null 2>&1
}

push_to_remote() {
  echo "Отправляю изменения на удалённый репозиторий..."
  git push origin master > /dev/null 2>&1
}

echo "Проверяю статус репозитория..."
git_status=$(git status --porcelain)
if [[ -n "$git_status" ]]; then
  add_files
  commit_changes
  push_to_remote
else
  echo "Нет изменений для коммита."
fi