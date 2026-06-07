<?php

namespace App\Chat\Domain\Enum;

enum ChatRoleEnum: string
{
    case RoleChatUser = 'ROLE_CHAT_USER';
    case RoleCreateChat = 'ROLE_CREATE_CHAT';
    case RoleListChats = 'ROLE_LIST_CHATS';
}
