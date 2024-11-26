#!/bin/bash

add_files() {
  echo "Добавляю изменённые файлы..."
  git add . > /dev/null 2>&1
}

commit_changes() {
  read -p "Введите сообщение для коммита: " commit_message
  echo "Делаем коммит с сообщением: $commit_message"
  git commit -m "$commit_message" > /dev/null 2>&1
}
й
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